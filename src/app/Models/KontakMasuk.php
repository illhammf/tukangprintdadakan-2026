<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontakMasuk extends Model
{
    protected $fillable = [
        'nama',
        'email',
        'nomor_whatsapp',
        'subjek',
        'pesan',
        'status_pesan',
    ];
}