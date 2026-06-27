<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pesanan extends Model
{
    protected $fillable = [
        'user_id',
        'kode_pesanan',
        'nama_pelanggan',
        'email',
        'nomor_whatsapp',
        'tanggal_pesan',
        'tanggal_pengambilan',
        'jam_pengambilan',
        'lokasi_pengambilan',
        'detail_lokasi',
        'catatan',
        'subtotal',
        'biaya_tambahan',
        'biaya_pengiriman',
        'total_harga',
        'status_pesanan',
    ];

    protected $casts = [
        'tanggal_pesan' => 'date',
        'tanggal_pengambilan' => 'date',
        'jam_pengambilan' => 'datetime:H:i',
        'subtotal' => 'decimal:2',
        'biaya_tambahan' => 'decimal:2',
        'biaya_pengiriman' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detailPesanans(): HasMany
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function pengiriman(): HasOne
    {
        return $this->hasOne(Pengiriman::class);
    }

    public function riwayatStatusPesanans(): HasMany
    {
        return $this->hasMany(RiwayatStatusPesanan::class);
    }
}