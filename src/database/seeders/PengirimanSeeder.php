<?php

namespace Database\Seeders;

use App\Models\Pengiriman;
use App\Models\Pesanan;
use Illuminate\Database\Seeder;

class PengirimanSeeder extends Seeder
{
    public function run(): void
    {
        $pesanan = Pesanan::first();

        if (!$pesanan) {
            return;
        }

        Pengiriman::updateOrCreate(
            [
                'pesanan_id' => $pesanan->id,
            ],
            [
                'metode_pengiriman' => 'ambil_di_kampus',
                'biaya_pengiriman' => 0,
                'status_pengiriman' => 'belum_dikirim',
                'catatan_pengiriman' => 'Diambil langsung di kampus.',
            ]
        );
    }
}