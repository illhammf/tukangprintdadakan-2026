<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatStatusPesanan extends Model
{
    protected $fillable = [
        'pesanan_id',
        'status',
        'catatan',
        'waktu_status',
    ];

    protected $casts = [
        'waktu_status' => 'datetime',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }
}