<?php

namespace App\Filament\Admin\Widgets;

use App\Models\KontakMasuk;
use App\Models\Layanan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatistikDashboard extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Pesanan', Pesanan::count())
                ->description('Semua pesanan yang masuk')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Menunggu Verifikasi', Pesanan::where('status_pesanan', 'menunggu_verifikasi')->count())
                ->description('Pesanan baru perlu dicek')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Sedang Diproses', Pesanan::where('status_pesanan', 'diproses')->count())
                ->description('Pesanan dalam pengerjaan')
                ->descriptionIcon('heroicon-m-cog-6-tooth')
                ->color('info'),

            Stat::make('Siap Diambil', Pesanan::where('status_pesanan', 'siap_diambil')->count())
                ->description('Pesanan siap diserahkan')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('success'),

            Stat::make('Pendapatan Lunas', 'Rp ' . number_format(Pembayaran::where('status_pembayaran', 'lunas')->sum('jumlah_bayar'), 0, ',', '.'))
                ->description('Total pembayaran lunas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Pesan Masuk Baru', KontakMasuk::where('status_pesan', 'baru')->count())
                ->description('Pesan pelanggan belum dibaca')
                ->descriptionIcon('heroicon-m-inbox-stack')
                ->color('danger'),

            Stat::make('Layanan Aktif', Layanan::where('status', true)->count())
                ->description('Layanan tampil di website')
                ->descriptionIcon('heroicon-m-printer')
                ->color('primary'),
        ];
    }
}