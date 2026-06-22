<?php

namespace Database\Seeders;

use App\Models\PengaturanWebsite;
use Illuminate\Database\Seeder;

class PengaturanWebsiteSeeder extends Seeder
{
    public function run(): void
    {
        PengaturanWebsite::updateOrCreate(
            ['id' => 1],
            [
                'nama_website' => 'Tukang Print Dadakan',
                'hero_title' => 'Tukang Print Dadakan',
                'hero_subtitle' => 'Solusi cepat dan mudah untuk kebutuhan print mahasiswa.',
                'nomor_whatsapp' => '08xxxxxxxxxx',
                'email' => 'tukangprint@gmail.com',
                'alamat' => 'Kampus UEU Tangerang',
                'jam_operasional' => 'Senin - Jumat, kecuali tanggal merah',
            ]
        );
    }
}