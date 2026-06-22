<?php

namespace Database\Seeders;

use App\Models\PengaturanBooking;
use Illuminate\Database\Seeder;

class PengaturanBookingSeeder extends Seeder
{
    public function run(): void
    {
        PengaturanBooking::updateOrCreate(
            ['id' => 1],
            [
                'nama_pengaturan' => 'Pengaturan Booking Utama',
                'wajib_h_minus_satu' => true,
                'batas_jam_booking' => '22:00:00',
                'tutup_sabtu' => false,
                'tutup_minggu' => true,
                'tutup_tanggal_merah' => true,
                'maksimal_lembar_per_hari' => 500,
                'maksimal_lembar_per_order' => 100,
                'maksimal_jadwal_belajar_per_jam' => 1,
                'minimal_hari_rapihin_tugas' => 2,
                'biaya_jilid' => 5000,
                'biaya_laminating' => 3000,
                'biaya_prioritas' => 5000,
                'aktifkan_order_prioritas' => false,
                'wajib_upload_bukti_online' => false,
                'ongkir_kampus' => 0,
                'lokasi_luar_kampus_perlu_konfirmasi' => true,
                'ojek_online_perlu_konfirmasi' => true,
                'catatan_booking' => 'Pesanan wajib dilakukan H-1 sebelum pengambilan.',
            ]
        );
    }
}