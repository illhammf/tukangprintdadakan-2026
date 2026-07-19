<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPesanan extends Model
{
    protected $fillable = [
        'pesanan_id',
        'layanan_id',
        'nama_file',
        'file_path',
        'jenis_print',
        'ukuran_kertas',
        'jumlah_halaman',
        'jumlah_copy',
        'harga_satuan',
        'subtotal',
        'pakai_jilid',
        'pakai_laminating',
        'catatan_detail',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'pakai_jilid' => 'boolean',
        'pakai_laminating' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (DetailPesanan $detailPesanan) {
            $detailPesanan->pesanan?->updateRingkasanBiaya();
        });

        static::deleted(function (DetailPesanan $detailPesanan) {
            $detailPesanan->pesanan?->updateRingkasanBiaya();
        });
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }
}