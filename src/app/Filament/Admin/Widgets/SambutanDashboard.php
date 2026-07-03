<?php

namespace App\Filament\Admin\Widgets;

use App\Models\PengaturanWebsite;
use App\Models\Pesanan;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class SambutanDashboard extends Widget
{
    protected static string $view = 'filament.admin.widgets.sambutan-dashboard';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    protected function getViewData(): array
    {
        $now = now(config('app.timezone', 'Asia/Jakarta'));

        $hour = (int) $now->format('H');

        if ($hour >= 5 && $hour < 11) {
            $greeting = 'Selamat Pagi ☀️';
        } elseif ($hour >= 11 && $hour < 15) {
            $greeting = 'Selamat Siang 🌤️';
        } elseif ($hour >= 15 && $hour < 18) {
            $greeting = 'Selamat Sore 🌥️';
        } else {
            $greeting = 'Selamat Malam 🌙';
        }

        $website = PengaturanWebsite::first();

        return [
            'nama' => Auth::user()?->name ?? 'Admin',

            'namaWebsite' => $website?->nama_website ?? 'Tukang Print Dadakan',

            'logo' => $website?->logo,

            'greeting' => $greeting,

            'tanggal' => $now
                ->copy()
                ->locale('id')
                ->translatedFormat('l, d F Y'),

            'jam' => $now->format('H.i'),

            'pesananHariIni' => Pesanan::query() // Untuk menghitung jumlah pesanan yang dibuat hari ini
                ->whereDate('created_at', $now->toDateString())
                ->count(),

            'perluVerifikasi' => Pesanan::query() // Untuk menghitung jumlah pesanan yang perlu diverifikasi
                ->where('status_pesanan', 'menunggu_verifikasi')
                ->count(),

            'pengambilanBesok' => Pesanan::query() // Untuk menghitung jumlah pesanan yang dijadwalkan untuk pengambilan besok
                ->whereDate('tanggal_pengambilan', $now->copy()->addDay()->toDateString())
                ->whereNotIn('status_pesanan', ['selesai', 'dibatalkan'])
                ->count(),
        ];
    }
}