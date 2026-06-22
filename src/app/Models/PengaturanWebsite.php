<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanWebsite extends Model
{
    protected $fillable = [
        'nama_website',
        'logo',
        'favicon',
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'nomor_whatsapp',
        'email',
        'alamat',
        'jam_operasional',
    ];
}