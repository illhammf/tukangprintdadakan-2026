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

    public function updateRingkasanBiaya(): void
    {
        $subtotal = (float) $this->detailPesanans()->sum('subtotal');
        $biayaTambahan = (float) ($this->biaya_tambahan ?? 0);
        $biayaPengiriman = (float) ($this->biaya_pengiriman ?? 0);

        $this->forceFill([
            'subtotal' => $subtotal,
            'total_harga' => $subtotal + $biayaTambahan + $biayaPengiriman,
        ])->saveQuietly();
    }

    protected static function booted(): void
    {
        static::creating(function (Pesanan $pesanan) {
            if (blank($pesanan->kode_pesanan)) {
                $tanggal = now()->format('Ymd');

                $nomorUrut = Pesanan::whereDate('created_at', today())->count() + 1;

                $pesanan->kode_pesanan = 'TPD-' . $tanggal . '-' . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);
            }

            if (blank($pesanan->tanggal_pesan)) {
                $pesanan->tanggal_pesan = now();
            }
        });

        static::created(function (Pesanan $pesanan) {
            $pesanan->riwayatStatusPesanans()->create([
                'status' => $pesanan->status_pesanan,
                'catatan' => 'Pesanan berhasil dibuat.',
                'waktu_status' => now(),
            ]);
        });

        static::updated(function (Pesanan $pesanan) {
            if ($pesanan->wasChanged('status_pesanan')) {
                $pesanan->riwayatStatusPesanans()->create([
                    'status' => $pesanan->status_pesanan,
                    'catatan' => 'Status pesanan berubah menjadi ' . str_replace('_', ' ', $pesanan->status_pesanan) . '.',
                    'waktu_status' => now(),
                ]);
            }
        });
    }
}