<?php

namespace Database\Seeders;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Database\Seeder;

class PembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $pesanan = Pesanan::first();

        if (!$pesanan) {
            return;
        }

        Pembayaran::updateOrCreate(
            [
                'pesanan_id' => $pesanan->id,
            ],
            [
                'metode_pembayaran' => 'cash',
                'channel_pembayaran' => null,
                'jumlah_bayar' => 0,
                'status_pembayaran' => 'belum_bayar',
            ]
        );
    }
}