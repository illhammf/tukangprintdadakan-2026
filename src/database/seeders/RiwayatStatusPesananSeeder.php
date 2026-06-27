<?php

namespace Database\Seeders;

use App\Models\Pesanan;
use App\Models\RiwayatStatusPesanan;
use Illuminate\Database\Seeder;

class RiwayatStatusPesananSeeder extends Seeder
{
    public function run(): void
    {
        $pesanan = Pesanan::first();

        if (!$pesanan) {
            return;
        }

        RiwayatStatusPesanan::updateOrCreate(
            [
                'pesanan_id' => $pesanan->id,
                'status' => 'menunggu_verifikasi',
            ],
            [
                'catatan' => 'Pesanan berhasil dibuat.',
                'waktu_status' => now(),
            ]
        );
    }
}