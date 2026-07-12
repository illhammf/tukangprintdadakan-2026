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

        $biayaPengiriman = (float) (
            $this->pengiriman()
                ->value('biaya_pengiriman') ?? 0
        );

        $totalHarga = $subtotal + $biayaTambahan + $biayaPengiriman;

        $this->forceFill([
            'subtotal' => $subtotal,
            'biaya_pengiriman' => $biayaPengiriman,
            'total_harga' => $totalHarga,
        ])->saveQuietly();

        $this->pembayaran()
            ->where('status_pembayaran', '!=', 'lunas')
            ->update([
                'jumlah_bayar' => $totalHarga,
            ]);
    }

    public function ubahStatus(string $status, ?string $catatan = null): void
    {
        if ($this->status_pesanan === $status) {
            return;
        }

        if ($status === 'selesai' && $this->pembayaran?->status_pembayaran !== 'lunas') {
            throw new \Exception('Pesanan belum bisa diselesaikan karena pembayaran belum lunas.');
        }

        $this->forceFill([
            'status_pesanan' => $status,
        ])->saveQuietly();

        $this->riwayatStatusPesanans()->create([
            'status' => $status,
            'catatan' => $catatan ?? 'Status pesanan berubah menjadi ' . str_replace('_', ' ', $status) . '.',
            'waktu_status' => now(),
        ]);
    }

    protected static function booted(): void
    {
        static::creating(function (Pesanan $pesanan) {
            if (blank($pesanan->kode_pesanan)) {
                $tanggal = now()->format('Ymd');

                $jumlahPesananHariIni = self::whereDate('created_at', today())->count() + 1;

                $pesanan->kode_pesanan = 'TPD-' . $tanggal . '-' . str_pad($jumlahPesananHariIni, 4, '0', STR_PAD_LEFT);
            }

            if (blank($pesanan->tanggal_pesan)) {
                $pesanan->tanggal_pesan = now();
            }

            if (blank($pesanan->status_pesanan)) {
                $pesanan->status_pesanan = 'menunggu_verifikasi';
            }
        });

        static::created(function (Pesanan $pesanan) {
            $pesanan->riwayatStatusPesanans()->create([
                'status' => $pesanan->status_pesanan ?? 'menunggu_verifikasi',
                'catatan' => 'Pesanan berhasil dibuat.',
                'waktu_status' => now(),
            ]);

            $metodePengiriman = match ($pesanan->lokasi_pengambilan) {
                'Ojek Online' => 'ojek_online',
                'Diantar' => 'antar',
                default => 'ambil_di_kampus',
            };

            $biayaPengiriman = $pesanan->lokasi_pengambilan === 'Diantar' ? 5000 : 0;

            $pesanan->pengiriman()->create([
                'metode_pengiriman' => $metodePengiriman,
                'alamat_pengiriman' => $pesanan->detail_lokasi,
                'biaya_pengiriman' => $biayaPengiriman,
                'status_pengiriman' => 'belum_dikirim',
                'catatan_pengiriman' => null,
            ]);

            $pesanan->updateRingkasanBiaya();
        });

        static::updated(function (Pesanan $pesanan) {
            if (! $pesanan->wasChanged([
                'lokasi_pengambilan',
                'detail_lokasi',
            ])) {
                return;
            }

            $metodePengiriman = match ($pesanan->lokasi_pengambilan) {
                'Ojek Online' => 'ojek_online',
                'Diantar' => 'antar',
                default => 'ambil_di_kampus',
            };

            $biayaPengiriman = $pesanan->lokasi_pengambilan === 'Diantar'
                ? 5000
                : 0;

            $pesanan->pengiriman()->updateOrCreate(
                [],
                [
                    'metode_pengiriman' => $metodePengiriman,
                    'alamat_pengiriman' => $pesanan->detail_lokasi,
                    'biaya_pengiriman' => $biayaPengiriman,
                ]
            );

            $pesanan->updateRingkasanBiaya();
        });
    }
}