<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;

        if (! $orderId || ! $statusCode || ! $grossAmount || ! $signatureKey) {
            return response()->json([
                'message' => 'Invalid notification payload.',
            ], 400);
        }

        $expectedSignature = hash(
            'sha512',
            $orderId . $statusCode . $grossAmount . config('midtrans.server_key')
        );

        if (! hash_equals($expectedSignature, $signatureKey)) {
            Log::warning('Invalid Midtrans signature', $payload);

            return response()->json([
                'message' => 'Invalid signature.',
            ], 403);
        }

        $pembayaran = Pembayaran::query()
            ->with('pesanan')
            ->where('midtrans_order_id', $orderId)
            ->first();

        if (! $pembayaran) {
            return response()->json([
                'message' => 'Payment not found.',
            ], 404);
        }

        $updateData = [
            'transaction_id' => $payload['transaction_id'] ?? null,
            'payment_type' => $payload['payment_type'] ?? null,
            'fraud_status' => $payload['fraud_status'] ?? null,
            'midtrans_response' => $payload,
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

            return response()->json([
                'message' => 'Payment marked as paid.',
            ]);
        }

        if ($transactionStatus === 'pending') {
            $updateData['status_pembayaran'] = 'belum_bayar';

            $pembayaran->update($updateData);

            return response()->json([
                'message' => 'Payment is pending.',
            ]);
        }

        if (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'], true)) {
            $updateData['status_pembayaran'] = 'ditolak';

            $pembayaran->update($updateData);

            return response()->json([
                'message' => 'Payment failed or expired.',
            ]);
        }

        $pembayaran->update($updateData);

        return response()->json([
            'message' => 'Notification received.',
        ]);
    }
}