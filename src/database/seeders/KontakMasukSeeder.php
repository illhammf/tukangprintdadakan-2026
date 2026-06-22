<?php

namespace Database\Seeders;

use App\Models\KontakMasuk;
use Illuminate\Database\Seeder;

class KontakMasukSeeder extends Seeder
{
    public function run(): void
    {
        KontakMasuk::create([
            'nama' => 'Contoh Pelanggan',
            'email' => 'pelanggan@example.com',
            'nomor_whatsapp' => '081234567890',
            'subjek' => 'Pertanyaan Layanan Print',
            'pesan' => 'Apakah bisa print warna untuk tugas besok?',
            'status_pesan' => 'baru',
        ]);
    }
}