<?php

namespace App\Services;

use App\Models\Pesanan;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');

            if (blank(config('midtrans.server_key')) || blank(config('midtrans.client_key'))) {
        throw new \RuntimeException('Konfigurasi Midtrans belum terbaca. Periksa MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY di file .env.');
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');
    }

    public function createSnapTransaction(Pesanan $pesanan): object
    {
        $pesanan->loadMissing(['detailPesanans.layanan', 'pembayaran']);

        $pembayaran = $pesanan->pembayaran;

        $midtransOrderId = $pembayaran?->midtrans_order_id
            ?: $pesanan->kode_pesanan . '-PAY-' . $pembayaran->id;

        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => (int) round($pesanan->total_harga),
            ],

            'customer_details' => [
                'first_name' => $pesanan->nama_pelanggan,
                'email' => $pesanan->email,
                'phone' => $pesanan->nomor_whatsapp,
            ],

            'item_details' => [
                [
                    'id' => $pesanan->kode_pesanan,
                    'price' => (int) round($pesanan->total_harga),
                    'quantity' => 1,
                    'name' => 'Pembayaran ' . $pesanan->kode_pesanan,
                ],
            ],

            'callbacks' => [
                'finish' => route('midtrans.finish', ['pesanan' => $pesanan->id]),
            ],
        ];

        $transaction = Snap::createTransaction($params);

        $pembayaran->update([
            'midtrans_order_id' => $midtransOrderId,
            'snap_token' => $transaction->token ?? null,
            'snap_redirect_url' => $transaction->redirect_url ?? null,
            'channel_pembayaran' => 'Midtrans',
        ]);

        return $transaction;
    }
}