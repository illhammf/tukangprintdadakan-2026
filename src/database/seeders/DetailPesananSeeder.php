<?php

namespace Database\Seeders;

use App\Models\DetailPesanan;
use App\Models\Layanan;
use App\Models\Pesanan;
use Illuminate\Database\Seeder;

class DetailPesananSeeder extends Seeder
{
    public function run(): void
    {
        $pesanan = Pesanan::first();
        $layanan = Layanan::first();

        if (!$pesanan || !$layanan) {
            return;
        }

        DetailPesanan::updateOrCreate(
            [
                'pesanan_id' => $pesanan->id,
                'layanan_id' => $layanan->id,
            ],
            [
                'nama_file' => 'contoh_tugas.pdf',
                'file_path' => 'pesanan/contoh_tugas.pdf',
                'jenis_print' => 'hitam_putih',
                'ukuran_kertas' => 'A4',
                'jumlah_halaman' => 10,
                'jumlah_copy' => 1,
                'harga_satuan' => 500,
                'subtotal' => 5000,
                'pakai_jilid' => false,
                'pakai_laminating' => false,
                'catatan_detail' => 'Print satu sisi.',
            ]
        );
    }
}