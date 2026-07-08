<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Transaction;

class MidtransReturnController extends Controller
{
    public function finish(Request $request, ?Pesanan $pesanan = null)
    {
        $orderId = $request->query('order_id');

        $pembayaran = null;

        if ($orderId) {
            $pembayaran = Pembayaran::query()
                ->with('pesanan')
                ->where('midtrans_order_id', $orderId)
                ->first();
        }

        if (! $pembayaran && $pesanan) {
            $pesanan->load('pembayaran');

            $pembayaran = $pesanan->pembayaran;
            $orderId = $pembayaran?->midtrans_order_id;
        }

        if (! $pembayaran || ! $orderId) {
            return redirect()
                ->route('customer.pesanan.index')
                ->with('error', 'Data pembayaran Midtrans tidak ditemukan. Silakan cek detail pesanan atau hubungi admin.');
        }

        $this->configureMidtrans();

        $statusArray = [];

        if ($request->filled('transaction_status')) {
            $statusArray = $request->query();
            $statusArray['status_check_source'] = 'return_url_query';
        } else {
            try {
                $status = Transaction::status($orderId);

                $statusArray = json_decode(json_encode($status), true);
                $statusArray['status_check_source'] = 'midtrans_status_api';
            } catch (\Throwable $exception) {
                Log::warning('Midtrans status check failed on return URL', [
                    'order_id' => $orderId,
                    'message' => $exception->getMessage(),
                    'query' => $request->query(),
                ]);

                return redirect()
                    ->route('customer.pesanan.show', $pembayaran->pesanan)
                    ->with('error', 'Status pembayaran belum dapat diverifikasi dari Midtrans. Silakan cek beberapa saat lagi.');
            }
        }

        $message = $this->applyPaymentStatus($pembayaran, $statusArray);

        return redirect()
            ->route('customer.pesanan.show', $pembayaran->pesanan)
            ->with($message['type'], $message['text']);
    }

    public function check(Pesanan $pesanan)
    {
        abort_if($pesanan->user_id !== auth()->id(), 403);

        $pesanan->load('pembayaran');

        $pembayaran = $pesanan->pembayaran;

        if (! $pembayaran || ! $pembayaran->midtrans_order_id) {
            return back()->with('error', 'Data pembayaran Midtrans tidak ditemukan.');
        }

        $this->configureMidtrans();

        try {
            $status = Transaction::status($pembayaran->midtrans_order_id);

            $statusArray = json_decode(json_encode($status), true);
            $statusArray['status_check_source'] = 'manual_check_button';

            $message = $this->applyPaymentStatus($pembayaran, $statusArray);

            return back()->with($message['type'], $message['text']);
        } catch (\Throwable $exception) {
            Log::warning('Manual Midtrans status check failed', [
                'pesanan_id' => $pesanan->id,
                'order_id' => $pembayaran->midtrans_order_id,
                'message' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Status pembayaran belum dapat dicek. Silakan coba beberapa saat lagi.');
        }
    }

    private function applyPaymentStatus(Pembayaran $pembayaran, array $statusArray): array
    {
        $transactionStatus = $statusArray['transaction_status'] ?? null;

        $updateData = [
            'transaction_id' => $statusArray['transaction_id'] ?? $pembayaran->transaction_id,
            'payment_type' => $statusArray['payment_type'] ?? $pembayaran->payment_type,
            'fraud_status' => $statusArray['fraud_status'] ?? $pembayaran->fraud_status,
            'midtrans_response' => $statusArray,
        ];

        if (in_array($transactionStatus, ['capture', 'settlement'], true)) {
            $updateData['status_pembayaran'] = 'lunas';
            $updateData['tanggal_bayar'] = now();

            $pembayaran->update($updateData);

            if ($pembayaran->pesanan?->status_pesanan === 'menunggu_verifikasi') {
                $pembayaran->pesanan->ubahStatus(
                    'diproses',
                    'Pembayaran online melalui Midtrans berhasil. Pesanan mulai diproses.'
                );
            }

            return [
                'type' => 'success',
                'text' => 'Pembayaran berhasil. Status pembayaran telah diperbarui menjadi lunas.',
            ];
        }

        if ($transactionStatus === 'pending') {
            $updateData['status_pembayaran'] = 'belum_bayar';

            $pembayaran->update($updateData);

            return [
                'type' => 'error',
                'text' => 'Pembayaran masih pending. Selesaikan pembayaran sesuai instruksi Midtrans.',
            ];
        }

        if (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'], true)) {
            $updateData['status_pembayaran'] = 'ditolak';

            $pembayaran->update($updateData);

            return [
                'type' => 'error',
                'text' => 'Pembayaran gagal, dibatalkan, atau kedaluwarsa.',
            ];
        }

        $pembayaran->update($updateData);

        return [
            'type' => 'error',
            'text' => 'Status pembayaran belum dikenali. Silakan cek beberapa saat lagi.',
        ];
    }

    private function configureMidtrans(): void
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');
    }
}