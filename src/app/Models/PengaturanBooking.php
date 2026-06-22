<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanBooking extends Model
{
    protected $fillable = [
        'nama_pengaturan',
        'wajib_h_minus_satu',
        'batas_jam_booking',
        'tutup_sabtu',
        'tutup_minggu',
        'tutup_tanggal_merah',
        'maksimal_lembar_per_hari',
        'maksimal_lembar_per_order',
        'maksimal_jadwal_belajar_per_jam',
        'minimal_hari_rapihin_tugas',
        'biaya_jilid',
        'biaya_laminating',
        'biaya_prioritas',
        'aktifkan_order_prioritas',
        'wajib_upload_bukti_online',
        'ongkir_kampus',
        'lokasi_luar_kampus_perlu_konfirmasi',
        'ojek_online_perlu_konfirmasi',
        'catatan_booking',
    ];

    protected $casts = [
        'wajib_h_minus_satu' => 'boolean',
        'tutup_sabtu' => 'boolean',
        'tutup_minggu' => 'boolean',
        'tutup_tanggal_merah' => 'boolean',
        'aktifkan_order_prioritas' => 'boolean',
        'wajib_upload_bukti_online' => 'boolean',
        'lokasi_luar_kampus_perlu_konfirmasi' => 'boolean',
        'ojek_online_perlu_konfirmasi' => 'boolean',
        'biaya_jilid' => 'decimal:2',
        'biaya_laminating' => 'decimal:2',
        'biaya_prioritas' => 'decimal:2',
        'ongkir_kampus' => 'decimal:2',
    ];
}