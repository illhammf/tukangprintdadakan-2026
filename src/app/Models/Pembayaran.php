<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    protected $fillable = [
        'pesanan_id',
        'metode_pembayaran',
        'channel_pembayaran',
        'jumlah_bayar',
        'bukti_pembayaran',
        'status_pembayaran',
        'tanggal_bayar',
        'midtrans_order_id',
        'snap_token',
        'snap_redirect_url',
        'transaction_id',
        'payment_type',
        'fraud_status',
        'midtrans_response',
    ];

    protected $casts = [
        'jumlah_bayar' => 'decimal:2',
        'tanggal_bayar' => 'datetime',
        'midtrans_response' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function (Pembayaran $pembayaran) {
            $pesanan = $pembayaran->pesanan;

            if ($pesanan) {
                $pembayaran->jumlah_bayar = $pesanan->total_harga;

                if ($pembayaran->status_pembayaran === 'lunas' && blank($pembayaran->tanggal_bayar)) {
                    $pembayaran->tanggal_bayar = now();
                }
            }
        });
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }
}