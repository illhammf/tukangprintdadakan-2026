<?php

namespace Database\Seeders;

use App\Models\KategoriLayanan;
use App\Models\Layanan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        $print = KategoriLayanan::where('slug', 'print-dokumen')->first();
        $fotokopi = KategoriLayanan::where('slug', 'fotokopi')->first();
        $jilid = KategoriLayanan::where('slug', 'jilid')->first();
        $laminating = KategoriLayanan::where('slug', 'laminating')->first();
        $tambahan = KategoriLayanan::where('slug', 'layanan-tambahan')->first();

        $data = [

            [
                'kategori_layanan_id' => $print?->id,
                'nama_layanan' => 'Print Hitam Putih',
                'deskripsi' => 'Layanan cetak dokumen hitam putih ukuran A4.',
                'harga_dasar' => 500,
                'satuan' => 'lembar',
                'butuh_upload_file' => true,
            ],

            [
                'kategori_layanan_id' => $print?->id,
                'nama_layanan' => 'Print Warna',
                'deskripsi' => 'Layanan cetak dokumen berwarna ukuran A4.',
                'harga_dasar' => 900,
                'satuan' => 'lembar',
                'butuh_upload_file' => true,
            ],

            [
                'kategori_layanan_id' => $fotokopi?->id,
                'nama_layanan' => 'Fotokopi',
                'deskripsi' => 'Layanan fotokopi dokumen.',
                'harga_dasar' => 500,
                'satuan' => 'lembar',
                'butuh_upload_file' => false,
            ],

            [
                'kategori_layanan_id' => $jilid?->id,
                'nama_layanan' => 'Jilid Spiral',
                'deskripsi' => 'Jilid spiral untuk tugas, laporan, dan skripsi.',
                'harga_dasar' => 5000,
                'satuan' => 'jilid',
                'butuh_upload_file' => false,
            ],

            [
                'kategori_layanan_id' => $laminating?->id,
                'nama_layanan' => 'Laminating',
                'deskripsi' => 'Laminating dokumen ukuran A4.',
                'harga_dasar' => 3000,
                'satuan' => 'lembar',
                'butuh_upload_file' => false,
            ],

            [
                'kategori_layanan_id' => $tambahan?->id,
                'nama_layanan' => 'Scan Dokumen',
                'deskripsi' => 'Layanan scan dokumen ke format PDF.',
                'harga_dasar' => 1000,
                'satuan' => 'lembar',
                'butuh_upload_file' => false,
            ],

        ];

        foreach ($data as $item) {

            Layanan::updateOrCreate(
                [
                    'slug' => Str::slug($item['nama_layanan'])
                ],
                [
                    'kategori_layanan_id' => $item['kategori_layanan_id'],
                    'nama_layanan' => $item['nama_layanan'],
                    'deskripsi' => $item['deskripsi'],
                    'harga_dasar' => $item['harga_dasar'],
                    'satuan' => $item['satuan'],
                    'butuh_upload_file' => $item['butuh_upload_file'],
                    'bisa_online' => true,
                    'status' => true,
                ]
            );
        }
    }
}