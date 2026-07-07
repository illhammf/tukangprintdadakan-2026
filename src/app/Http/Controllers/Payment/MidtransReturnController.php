<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\Request;
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

        try {
            $status = Transaction::status($orderId);
        } catch (\Throwable $exception) {
            return redirect()
                ->route('customer.pesanan.show', $pembayaran->pesanan)
                ->with('error', 'Status pembayaran belum dapat diverifikasi dari Midtrans. Silakan cek beberapa saat lagi.');
        }

        $statusArray = json_decode(json_encode($status), true);

        $transactionStatus = $statusArray['transaction_status'] ?? null;

        $updateData = [
            'transaction_id' => $statusArray['transaction_id'] ?? null,
            'payment_type' => $statusArray['payment_type'] ?? null,
            'fraud_status' => $statusArray['fraud_status'] ?? null,
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

            return redirect()
                ->route('customer.pesanan.show', $pembayaran->pesanan)
                ->with('success', 'Pembayaran berhasil. Status pembayaran telah diperbarui menjadi lunas.');
        }

        if ($transactionStatus === 'pending') {
            $updateData['status_pembayaran'] = 'belum_bayar';

            $pembayaran->update($updateData);

            return redirect()
                ->route('customer.pesanan.show', $pembayaran->pesanan)
                ->with('error', 'Pembayaran masih pending. Selesaikan pembayaran sesuai instruksi Midtrans.');
        }

        if (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'], true)) {
            $updateData['status_pembayaran'] = 'ditolak';

            $pembayaran->update($updateData);

            return redirect()
                ->route('customer.pesanan.show', $pembayaran->pesanan)
                ->with('error', 'Pembayaran gagal, dibatalkan, atau kedaluwarsa.');
        }

        $pembayaran->update($updateData);

        return redirect()
            ->route('customer.pesanan.show', $pembayaran->pesanan)
            ->with('success', 'Status pembayaran berhasil diperiksa.');
    }

    private function configureMidtrans(): void
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');
    }
}