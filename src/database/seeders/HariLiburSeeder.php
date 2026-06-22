<?php

namespace Database\Seeders;

use App\Models\HariLibur;
use Illuminate\Database\Seeder;

class HariLiburSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'tanggal' => '2026-01-01',
                'nama_libur' => 'Tahun Baru',
                'keterangan' => 'Libur nasional tahun baru.',
                'status' => true,
            ],
            [
                'tanggal' => '2026-08-17',
                'nama_libur' => 'Hari Kemerdekaan Indonesia',
                'keterangan' => 'Libur nasional.',
                'status' => true,
            ],
        ];

        foreach ($data as $item) {
            HariLibur::updateOrCreate(
                ['tanggal' => $item['tanggal']],
                $item
            );
        }
    }
}