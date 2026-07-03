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
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $pendapatanHariIni = Pembayaran::whereDate('tanggal_bayar', today())
            ->where('status_pembayaran', 'lunas')
            ->sum('jumlah_bayar');

        $pendapatanKemarin = Pembayaran::whereDate('tanggal_bayar', today()->subDay())
            ->where('status_pembayaran', 'lunas')
            ->sum('jumlah_bayar');

        $pendapatanBulanIni = Pembayaran::whereMonth('tanggal_bayar', now()->month)
            ->whereYear('tanggal_bayar', now()->year)
            ->where('status_pembayaran', 'lunas')
            ->sum('jumlah_bayar');

        $pesananHariIni = Pesanan::whereDate('created_at', today())->count();

        $pesananKemarin = Pesanan::whereDate('created_at', today()->subDay())->count();

        return [
            Stat::make('Total Pesanan', Pesanan::count())
                ->description($pesananHariIni . ' pesanan masuk hari ini')
                ->descriptionIcon($pesananHariIni >= $pesananKemarin ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart($this->getPesananChart())
                ->color('primary'),

            Stat::make('Menunggu Verifikasi', Pesanan::where('status_pesanan', 'menunggu_verifikasi')->count())
                ->description('Pesanan baru perlu dicek')
                ->descriptionIcon('heroicon-m-clock')
                ->chart($this->getStatusChart('menunggu_verifikasi'))
                ->color('warning'),

            Stat::make('Sedang Diproses', Pesanan::where('status_pesanan', 'diproses')->count())
                ->description('Pesanan sedang dikerjakan')
                ->descriptionIcon('heroicon-m-cog-6-tooth')
                ->chart($this->getStatusChart('diproses'))
                ->color('info'),

            Stat::make('Siap Diambil', Pesanan::where('status_pesanan', 'siap_diambil')->count())
                ->description('Pesanan siap diserahkan')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart($this->getStatusChart('siap_diambil'))
                ->color('success'),

            Stat::make('Pendapatan Hari Ini', 'Rp ' . number_format($pendapatanHariIni, 0, ',', '.'))
                ->description(
                    $pendapatanHariIni >= $pendapatanKemarin
                        ? 'Naik / sama dibanding kemarin'
                        : 'Turun dibanding kemarin'
                )
                ->descriptionIcon($pendapatanHariIni >= $pendapatanKemarin ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart($this->getPendapatanChart())
                ->color($pendapatanHariIni >= $pendapatanKemarin ? 'success' : 'danger'),

            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format($pendapatanBulanIni, 0, ',', '.'))
                ->description(now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-chart-bar')
                ->chart($this->getPendapatanBulanChart())
                ->color('success'),

            Stat::make('Layanan Aktif', Layanan::where('status', true)->count())
                ->description('Tampil di website')
                ->descriptionIcon('heroicon-m-printer')
                ->chart($this->getLayananChart())
                ->color('primary'),

            Stat::make('Pesan Masuk Baru', KontakMasuk::where('status_pesan', 'baru')->count())
                ->description('Belum dibaca admin')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->chart($this->getPesanMasukChart())
                ->color('danger'),
        ];
    }

    protected function getPesananChart(): array
    {
        return collect(range(6, 0))
            ->map(fn (int $day) => Pesanan::whereDate('created_at', today()->subDays($day))->count())
            ->toArray();
    }

    protected function getStatusChart(string $status): array
    {
        return collect(range(6, 0))
            ->map(fn (int $day) => Pesanan::where('status_pesanan', $status)
                ->whereDate('created_at', today()->subDays($day))
                ->count())
            ->toArray();
    }

    protected function getPendapatanChart(): array
    {
        return collect(range(6, 0))
            ->map(fn (int $day) => (int) Pembayaran::where('status_pembayaran', 'lunas')
                ->whereDate('tanggal_bayar', today()->subDays($day))
                ->sum('jumlah_bayar'))
            ->toArray();
    }

    protected function getPendapatanBulanChart(): array
    {
        return collect(range(5, 0))
            ->map(fn (int $month) => (int) Pembayaran::where('status_pembayaran', 'lunas')
                ->whereMonth('tanggal_bayar', now()->subMonths($month)->month)
                ->whereYear('tanggal_bayar', now()->subMonths($month)->year)
                ->sum('jumlah_bayar'))
            ->toArray();
    }

    protected function getLayananChart(): array
    {
        return [
            Layanan::where('status', true)->count(),
            Layanan::where('bisa_online', true)->count(),
            Layanan::where('butuh_upload_file', true)->count(),
            Layanan::count(),
        ];
    }

    protected function getPesanMasukChart(): array
    {
        return collect(range(6, 0))
            ->map(fn (int $day) => KontakMasuk::whereDate('created_at', today()->subDays($day))->count())
            ->toArray();
    }
}