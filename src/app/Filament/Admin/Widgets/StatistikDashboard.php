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
        $now = now(config('app.timezone', 'Asia/Jakarta'));

        $pendapatanHariIni = Pembayaran::query()
            ->whereDate('tanggal_bayar', $now->toDateString())
            ->where('status_pembayaran', 'lunas')
            ->sum('jumlah_bayar');

        $pendapatanKemarin = Pembayaran::query()
            ->whereDate('tanggal_bayar', $now->copy()->subDay()->toDateString())
            ->where('status_pembayaran', 'lunas')
            ->sum('jumlah_bayar');

        $pendapatanBulanIni = Pembayaran::query()
            ->whereMonth('tanggal_bayar', $now->month)
            ->whereYear('tanggal_bayar', $now->year)
            ->where('status_pembayaran', 'lunas')
            ->sum('jumlah_bayar');

        $pesananHariIni = Pesanan::query()
            ->whereDate('created_at', $now->toDateString())
            ->count();

        $pesananKemarin = Pesanan::query()
            ->whereDate('created_at', $now->copy()->subDay()->toDateString())
            ->count();

        $layananAktif = Layanan::query()
            ->where('status', true)
            ->where('bisa_online', true)
            ->whereHas('kategoriLayanan', fn ($query) => $query->where('status', true))
            ->count();

        return [
            Stat::make('Total Pesanan', Pesanan::query()->count())
                ->description($pesananHariIni . ' pesanan masuk hari ini')
                ->descriptionIcon(
                    $pesananHariIni >= $pesananKemarin
                        ? 'heroicon-m-arrow-trending-up'
                        : 'heroicon-m-arrow-trending-down'
                )
                ->chart($this->getPesananChart())
                ->color('primary'),

            Stat::make('Menunggu Verifikasi', Pesanan::query()->where('status_pesanan', 'menunggu_verifikasi')->count())
                ->description('Pesanan baru perlu dicek')
                ->descriptionIcon('heroicon-m-clock')
                ->chart($this->getStatusChart('menunggu_verifikasi'))
                ->color('warning'),

            Stat::make('Sedang Diproses', Pesanan::query()->where('status_pesanan', 'diproses')->count())
                ->description('Pesanan sedang dikerjakan')
                ->descriptionIcon('heroicon-m-cog-6-tooth')
                ->chart($this->getStatusChart('diproses'))
                ->color('info'),

            Stat::make('Siap Diambil', Pesanan::query()->where('status_pesanan', 'siap_diambil')->count())
                ->description('Pesanan siap diserahkan')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart($this->getStatusChart('siap_diambil'))
                ->color('success'),

            Stat::make('Pendapatan Hari Ini', 'Rp ' . number_format((float) $pendapatanHariIni, 0, ',', '.'))
                ->description(
                    $pendapatanHariIni >= $pendapatanKemarin
                        ? 'Naik / sama dibanding kemarin'
                        : 'Turun dibanding kemarin'
                )
                ->descriptionIcon(
                    $pendapatanHariIni >= $pendapatanKemarin
                        ? 'heroicon-m-arrow-trending-up'
                        : 'heroicon-m-arrow-trending-down'
                )
                ->chart($this->getPendapatanChart())
                ->color($pendapatanHariIni >= $pendapatanKemarin ? 'success' : 'danger'),

            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format((float) $pendapatanBulanIni, 0, ',', '.'))
                ->description($now->copy()->locale('id')->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-chart-bar')
                ->chart($this->getPendapatanBulanChart())
                ->color('success'),

            Stat::make('Layanan Aktif', $layananAktif)
                ->description('Tampil dan bisa dipesan online')
                ->descriptionIcon('heroicon-m-printer')
                ->chart($this->getLayananChart())
                ->color('primary'),

            Stat::make('Pesan Masuk Baru', KontakMasuk::query()->where('status_pesan', 'baru')->count())
                ->description('Belum dibaca admin')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->chart($this->getPesanMasukChart())
                ->color('danger'),
        ];
    }

    protected function getPesananChart(): array
    {
        $now = now(config('app.timezone', 'Asia/Jakarta'));

        return collect(range(6, 0))
            ->map(fn (int $day): int => Pesanan::query()
                ->whereDate('created_at', $now->copy()->subDays($day)->toDateString())
                ->count())
            ->toArray();
    }

    protected function getStatusChart(string $status): array
    {
        $now = now(config('app.timezone', 'Asia/Jakarta'));

        return collect(range(6, 0))
            ->map(fn (int $day): int => Pesanan::query()
                ->where('status_pesanan', $status)
                ->whereDate('created_at', $now->copy()->subDays($day)->toDateString())
                ->count())
            ->toArray();
    }

    protected function getPendapatanChart(): array
    {
        $now = now(config('app.timezone', 'Asia/Jakarta'));

        return collect(range(6, 0))
            ->map(fn (int $day): int => (int) Pembayaran::query()
                ->where('status_pembayaran', 'lunas')
                ->whereDate('tanggal_bayar', $now->copy()->subDays($day)->toDateString())
                ->sum('jumlah_bayar'))
            ->toArray();
    }

    protected function getPendapatanBulanChart(): array
    {
        $now = now(config('app.timezone', 'Asia/Jakarta'));

        return collect(range(5, 0))
            ->map(function (int $month) use ($now): int {
                $date = $now->copy()->subMonths($month);

                return (int) Pembayaran::query()
                    ->where('status_pembayaran', 'lunas')
                    ->whereMonth('tanggal_bayar', $date->month)
                    ->whereYear('tanggal_bayar', $date->year)
                    ->sum('jumlah_bayar');
            })
            ->toArray();
    }

    protected function getLayananChart(): array
    {
        return [
            Layanan::query()->where('status', true)->count(),
            Layanan::query()->where('status', true)->where('bisa_online', true)->count(),
            Layanan::query()->where('status', true)->where('butuh_upload_file', true)->count(),
            Layanan::query()->count(),
        ];
    }

    protected function getPesanMasukChart(): array
    {
        $now = now(config('app.timezone', 'Asia/Jakarta'));

        return collect(range(6, 0))
            ->map(fn (int $day): int => KontakMasuk::query()
                ->whereDate('created_at', $now->copy()->subDays($day)->toDateString())
                ->count())
            ->toArray();
    }
}