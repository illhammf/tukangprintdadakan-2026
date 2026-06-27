<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Carbon\Carbon; // Untuk mendapatkan tanggal dan waktu saat ini
use Filament\Widgets\Widget;

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

        return [

            'nama' => auth()->user()->name,

            'greeting' => $greeting,

            'tanggal' => Carbon::now()->translatedFormat('l, d F Y'),

            'jam' => Carbon::now()->format('H:i'),

            'pesananBaru' => Pesanan::where('status_pesanan', 'menunggu_verifikasi')->count(),

            'menungguPembayaran' => Pembayaran::where('status_pembayaran', 'menunggu_verifikasi')->count(),

            'ambilBesok' => Pesanan::whereDate(
                'tanggal_pengambilan',
                today()->addDay()
            )->count(),
        ];
    }
}