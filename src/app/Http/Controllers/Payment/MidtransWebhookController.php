<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->json()->all();

        if ($payload === []) {
            $payload = $request->all();
        }

        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = (string) ($payload['signature_key'] ?? '');
        $transactionStatus = (string) (
            $payload['transaction_status'] ?? ''
        );
        $fraudStatus = (string) ($payload['fraud_status'] ?? '');

        if (
            $orderId === ''
            || $statusCode === ''
            || $grossAmount === ''
            || $signatureKey === ''
            || $transactionStatus === ''
        ) {
            return response()->json([
                'message' => 'Invalid notification payload.',
            ], 400);
        }

        $serverKey = (string) config('midtrans.server_key');

        if ($serverKey === '') {
            Log::critical(
                'Midtrans server key is not configured.'
            );

            return response()->json([
                'message' => 'Payment configuration is unavailable.',
            ], 500);
        }

        $expectedSignature = hash(
            'sha512',
            $orderId
                . $statusCode
                . $grossAmount
                . $serverKey
        );

        if (! hash_equals($expectedSignature, $signatureKey)) {
            Log::warning('Invalid Midtrans signature.', [
                'order_id' => $orderId,
                'status_code' => $statusCode,
                'transaction_status' => $transactionStatus,
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Invalid signature.',
            ], 403);
        }

        try {
            return DB::transaction(function () use (
                $payload,
                $orderId,
                $statusCode,
                $grossAmount,
                $transactionStatus,
                $fraudStatus
            ): JsonResponse {
                $pembayaran = Pembayaran::query()
                    ->where('midtrans_order_id', $orderId)
                    ->lockForUpdate()
                    ->first();

                if (! $pembayaran) {
                    Log::warning(
                        'Midtrans payment record was not found.',
                        [
                            'order_id' => $orderId,
                        ]
                    );

                    return response()->json([
                        'message' => 'Payment not found.',
                    ], 404);
                }

                $pesanan = $pembayaran
                    ->pesanan()
                    ->lockForUpdate()
                    ->first();

                /*
                |--------------------------------------------------------------------------
                | Validate Transaction Amount
                |--------------------------------------------------------------------------
                */

                $databaseAmount = (float) (
                    $pembayaran->jumlah_bayar
                    ?: $pesanan?->total_harga
                    ?: 0
                );

                $expectedAmount = (int) round(
                    $databaseAmount
                );

                $notificationAmount = (int) round(
                    (float) $grossAmount
                );

                if (
                    $expectedAmount <= 0
                    || $notificationAmount !== $expectedAmount
                ) {
                    Log::warning(
                        'Midtrans gross amount does not match.',
                        [
                            'order_id' => $orderId,
                            'expected_amount' => $expectedAmount,
                            'notification_amount' => $notificationAmount,
                        ]
                    );

                    return response()->json([
                        'message' => 'Transaction amount mismatch.',
                    ], 422);
                }

                /*
                |--------------------------------------------------------------------------
                | Common Transaction Data
                |--------------------------------------------------------------------------
                */

                $updateData = [
                    'transaction_id' => (
                        $payload['transaction_id']
                        ?? $pembayaran->transaction_id
                    ),
                    'payment_type' => (
                        $payload['payment_type']
                        ?? $pembayaran->payment_type
                    ),
                    'fraud_status' => (
                        $payload['fraud_status']
                        ?? $pembayaran->fraud_status
                    ),
                    'midtrans_response' => $payload,
                ];

                $alreadyPaid =
                    $pembayaran->status_pembayaran === 'lunas';

                $isSettlement =
                    $transactionStatus === 'settlement'
                    && $statusCode === '200';

                $isAcceptedCapture =
                    $transactionStatus === 'capture'
                    && $statusCode === '200'
                    && $fraudStatus === 'accept';

                /*
                |--------------------------------------------------------------------------
                | Successful Payment
                |--------------------------------------------------------------------------
                */

                if ($isSettlement || $isAcceptedCapture) {
                    $updateData['status_pembayaran'] = 'lunas';

                    if (! $pembayaran->tanggal_bayar) {
                        $updateData['tanggal_bayar'] = now();
                    }

                    $pembayaran->update($updateData);

                    if (
                        $pesanan
                        && $pesanan->status_pesanan
                            === 'menunggu_verifikasi'
                    ) {
                        $pesanan->ubahStatus(
                            'diproses',
                            'Pembayaran online melalui Midtrans berhasil. Pesanan mulai diproses.'
                        );
                    }

                    return response()->json([
                        'message' => $alreadyPaid
                            ? 'Payment was already marked as paid.'
                            : 'Payment marked as paid.',
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Prevent Status Downgrade
                |--------------------------------------------------------------------------
                |
                | Notifikasi dapat datang kembali atau tidak berurutan.
                | Pembayaran yang sudah lunas tidak boleh kembali menjadi
                | pending, ditolak, kedaluwarsa, atau dibatalkan.
                |
                */

                if ($alreadyPaid) {
                    $pembayaran->update($updateData);

                    Log::info(
                        'Older Midtrans notification ignored.',
                        [
                            'order_id' => $orderId,
                            'transaction_status' => (
                                $transactionStatus
                            ),
                        ]
                    );

                    return response()->json([
                        'message' => 'Notification received and ignored.',
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Fraud Challenge
                |--------------------------------------------------------------------------
                */

                if (
                    $transactionStatus === 'capture'
                    && $fraudStatus === 'challenge'
                ) {
                    $updateData['status_pembayaran'] =
                        'menunggu_verifikasi';

                    $pembayaran->update($updateData);

                    return response()->json([
                        'message' => 'Payment requires verification.',
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Pending Payment
                |--------------------------------------------------------------------------
                */

                if ($transactionStatus === 'pending') {
                    $updateData['status_pembayaran'] =
                        'belum_bayar';

                    $pembayaran->update($updateData);

                    return response()->json([
                        'message' => 'Payment is pending.',
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Failed Payment
                |--------------------------------------------------------------------------
                */

                if (
                    in_array(
                        $transactionStatus,
                        [
                            'deny',
                            'cancel',
                            'expire',
                            'failure',
                        ],
                        true
                    )
                ) {
                    $updateData['status_pembayaran'] =
                        'ditolak';

                    $pembayaran->update($updateData);

                    return response()->json([
                        'message' => 'Payment failed or expired.',
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Other Status
                |--------------------------------------------------------------------------
                */

                $pembayaran->update($updateData);

                return response()->json([
                    'message' => 'Notification received.',
                ]);
            }, 3);
        } catch (Throwable $exception) {
            Log::error(
                'Failed to process Midtrans notification.',
                [
                    'order_id' => $orderId,
                    'transaction_status' => $transactionStatus,
                    'exception' => $exception->getMessage(),
                ]
            );

            return response()->json([
                'message' => 'Failed to process notification.',
            ], 500);
        }
    }
}