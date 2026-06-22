<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriLayanan extends Model
{
    protected $fillable = [
        'nama_kategori',
        'slug',
        'deskripsi',
        'gambar',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function layanans(): HasMany
    {
        return $this->hasMany(Layanan::class);
    }
}