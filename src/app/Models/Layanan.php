<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Layanan extends Model
{
    protected $fillable = [
        'kategori_layanan_id',
        'nama_layanan',
        'slug',
        'deskripsi',
        'harga_dasar',
        'satuan',
        'gambar',
        'butuh_upload_file',
        'bisa_online',
        'status',
    ];

    protected $casts = [
        'harga_dasar' => 'decimal:2',
        'butuh_upload_file' => 'boolean',
        'bisa_online' => 'boolean',
        'status' => 'boolean',
    ];

    public function kategoriLayanan(): BelongsTo
    {
        return $this->belongsTo(KategoriLayanan::class);
    }
}