<?php

namespace Database\Seeders;

use App\Models\KontakMasuk;
use Illuminate\Database\Seeder;

class KontakMasukSeeder extends Seeder
{
    public function run(): void
    {
        KontakMasuk::updateOrCreate( // Untuk menghindari duplikasi data, gunakan updateOrCreate
            [
                'email' => 'pelanggan@example.com',
                'subjek' => 'Pertanyaan Layanan Print',
            ],
            [
                'nama' => 'Contoh Pelanggan',
                'nomor_whatsapp' => '081234567890',
                'pesan' => 'Apakah bisa print warna untuk tugas besok?',
                'status_pesan' => 'baru',
            ]
        );
    }
}