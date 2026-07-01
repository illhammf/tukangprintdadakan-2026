<?php

namespace Database\Seeders;

use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PesananSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'user@admin.com')->first();

        Pesanan::updateOrCreate(
            ['kode_pesanan' => 'TPD-20260622-0001'],
            [
                'user_id' => $user?->id,
                'nama_pelanggan' => $user?->name ?? 'Pelanggan Contoh',
                'email' => $user?->email ?? 'pelanggan@example.com',
                'nomor_whatsapp' => $user?->nomor_whatsapp ?? '081234567890',
                'tanggal_pesan' => '2026-06-22',
                'tanggal_pengambilan' => '2026-06-23',
                'jam_pengambilan' => '10:00:00',
                'lokasi_pengambilan' => 'Kampus UEU Tangerang',
                'detail_lokasi' => 'COD area kampus.',
                'catatan' => 'Tolong dicetak rapi.',
                'subtotal' => 0,
                'biaya_tambahan' => 0,
                'biaya_pengiriman' => 0,
                'total_harga' => 0,
                'status_pesanan' => 'menunggu_verifikasi',
            ]
        );
    }
}