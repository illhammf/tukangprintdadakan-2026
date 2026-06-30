<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Carbon\Carbon; // Untuk mendapatkan tanggal dan waktu saat ini
use Filament\Widgets\Widget;
use App\Models\PengaturanWebsite;
use Illuminate\Support\Facades\Storage;

class SambutanDashboard extends Widget
{
    protected static string $view = 'filament.admin.widgets.sambutan-dashboard';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    protected function getViewData(): array
    {
        $hour = now()->hour;

        if ($hour >= 5 && $hour < 11) {
            $greeting = 'Selamat Pagi ☀️';
        } elseif ($hour >= 11 && $hour < 15) {
            $greeting = 'Selamat Siang 🌤️';
        } elseif ($hour >= 15 && $hour < 18) {
            $greeting = 'Selamat Sore 🌇';
        } else {
            $greeting = 'Selamat Malam 🌙';
        }
        $website = PengaturanWebsite::first();

        return [

            'nama' => auth()->user()->name,

            'namaWebsite' => $website?->nama_website,

            'logo' => $website?->logo,

            'greeting' => $greeting,

            'tanggal' => now()
                ->locale('id')
                ->translatedFormat('l, d F Y'),

            'jam' => now()->format('H.i'),

            'pesananHariIni' => Pesanan::whereDate(
                'created_at',
                today()
            )->count(),

            'perluVerifikasi' => Pembayaran::where(
                'status_pembayaran',
                'menunggu_verifikasi'
            )->count(),

            'pengambilanBesok' => Pesanan::whereDate(
                'tanggal_pengambilan',
                today()->addDay()
            )->count(),
        ];
    }
}