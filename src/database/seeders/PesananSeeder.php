<?php

namespace Database\Seeders;

use App\Models\DetailPesanan;
use App\Models\Layanan;
use App\Models\Pembayaran;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use App\Models\RiwayatStatusPesanan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PesananSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $layanan = Layanan::where('slug', 'print-dokumen')->first();

        if (! $layanan) {
            return;
        }

        $pesanan = Pesanan::updateOrCreate(
            ['kode_pesanan' => 'TPD-20260622-0001'],
            [
                'user_id' => $user?->id,
                'nama_pelanggan' => $user?->name ?? 'Pelanggan Contoh',
                'email' => $user?->email ?? 'pelanggan@example.com',
                'nomor_whatsapp' => '081234567890',
                'tanggal_pesan' => '2026-06-22',
                'tanggal_pengambilan' => '2026-06-23',
                'jam_pengambilan' => '10:00:00',
                'lokasi_pengambilan' => 'Kampus UEU Tangerang',
                'detail_lokasi' => 'COD area kampus.',
                'catatan' => 'Tolong dicetak rapi.',
                'subtotal' => 5000,
                'biaya_tambahan' => 0,
                'biaya_pengiriman' => 0,
                'total_harga' => 5000,
                'status_pesanan' => 'menunggu_verifikasi',
            ]
        );

        DetailPesanan::updateOrCreate(
            ['pesanan_id' => $pesanan->id, 'layanan_id' => $layanan->id],
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

        Pembayaran::updateOrCreate(
            ['pesanan_id' => $pesanan->id],
            [
                'metode_pembayaran' => 'cash',
                'channel_pembayaran' => null,
                'jumlah_bayar' => 0,
                'bukti_pembayaran' => null,
                'status_pembayaran' => 'belum_bayar',
                'tanggal_bayar' => null,
            ]
        );

        Pengiriman::updateOrCreate(
            ['pesanan_id' => $pesanan->id],
            [
                'metode_pengiriman' => 'ambil_di_kampus',
                'alamat_pengiriman' => null,
                'biaya_pengiriman' => 0,
                'status_pengiriman' => 'belum_dikirim',
                'catatan_pengiriman' => 'Diambil langsung di kampus.',
            ]
        );

        RiwayatStatusPesanan::updateOrCreate(
            ['pesanan_id' => $pesanan->id, 'status' => 'menunggu_verifikasi'],
            [
                'catatan' => 'Pesanan berhasil dibuat dan menunggu verifikasi admin.',
                'waktu_status' => now(),
            ]
        );
    }
}