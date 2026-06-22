<?php

namespace Database\Seeders;

use App\Models\KategoriLayanan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KategoriLayananSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama_kategori' => 'Print Dokumen',
                'deskripsi' => 'Kategori layanan untuk mencetak dokumen hitam putih maupun berwarna.',
                'status' => true,
            ],
            [
                'nama_kategori' => 'Fotokopi',
                'deskripsi' => 'Kategori layanan untuk kebutuhan fotokopi dokumen.',
                'status' => true,
            ],
            [
                'nama_kategori' => 'Jilid',
                'deskripsi' => 'Kategori layanan penjilidan dokumen seperti jilid spiral, lakban, dan lainnya.',
                'status' => true,
            ],
            [
                'nama_kategori' => 'Laminating',
                'deskripsi' => 'Kategori layanan laminating dokumen agar lebih awet dan terlindungi.',
                'status' => true,
            ],
            [
                'nama_kategori' => 'Layanan Tambahan',
                'deskripsi' => 'Kategori layanan tambahan seperti scan dokumen dan kebutuhan pendukung lainnya.',
                'status' => true,
            ],
        ];

        foreach ($data as $item) {
            KategoriLayanan::updateOrCreate(
                ['slug' => Str::slug($item['nama_kategori'])],
                [
                    'nama_kategori' => $item['nama_kategori'],
                    'deskripsi' => $item['deskripsi'],
                    'status' => $item['status'],
                ]
            );
        }
    }
}