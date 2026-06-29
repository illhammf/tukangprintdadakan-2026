<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengiriman extends Model
{
    protected $table = 'pengirimen';

    protected $fillable = [
        'pesanan_id',
        'metode_pengiriman',
        'alamat_pengiriman',
        'biaya_pengiriman',
        'status_pengiriman',
        'catatan_pengiriman',
    ];

    protected $casts = [
        'biaya_pengiriman' => 'decimal:2',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }

    protected static function booted(): void
    {
        static::saved(function (Pengiriman $pengiriman) {
            $pesanan = $pengiriman->pesanan;

            if ($pesanan) {
                $pesanan->forceFill([
                    'biaya_pengiriman' => $pengiriman->biaya_pengiriman ?? 0,
                ])->saveQuietly();

                $pesanan->updateRingkasanBiaya();
            }
        });

        static::deleted(function (Pengiriman $pengiriman) {
            $pesanan = $pengiriman->pesanan;

            if ($pesanan) {
                $pesanan->forceFill([
                    'biaya_pengiriman' => 0,
                ])->saveQuietly();

                $pesanan->updateRingkasanBiaya();
            }
        });
    }
}