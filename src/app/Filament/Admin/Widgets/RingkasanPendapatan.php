<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pembayaran;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class RingkasanPendapatan extends ChartWidget
{
    protected static ?string $heading = '📈 Ringkasan Pendapatan';

    protected static ?string $description = 'Pendapatan lunas 7 hari terakhir';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $labels = [];
        $data = [];

        foreach (range(6, 0) as $day) {
            $tanggal = Carbon::today()->subDays($day);

            $labels[] = $tanggal->translatedFormat('d M');

            $data[] = (int) Pembayaran::where('status_pembayaran', 'lunas')
                ->whereDate('tanggal_bayar', $tanggal)
                ->sum('jumlah_bayar');
        }

        $hariIni = $data[6] ?? 0;
        $kemarin = $data[5] ?? 0;

        $naik = $hariIni >= $kemarin;

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan',
                    'data' => $data,
                    'borderWidth' => 2,
                    'borderRadius' => 10,
                    'backgroundColor' => $naik
                        ? 'rgba(34, 197, 94, 0.35)'
                        : 'rgba(239, 68, 68, 0.35)',
                    'borderColor' => $naik
                        ? 'rgb(22, 163, 74)'
                        : 'rgb(220, 38, 38)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    public function getHeading(): string
    {
        $hariIni = $this->pendapatanHariIni();
        $kemarin = $this->pendapatanKemarin();

        $ikon = $hariIni >= $kemarin ? '📈' : '📉';

        return $ikon . ' Ringkasan Pendapatan';
    }

    public function getDescription(): ?string
    {
        return 'Hari ini Rp ' . number_format($this->pendapatanHariIni(), 0, ',', '.')
            . ' • Minggu ini Rp ' . number_format($this->pendapatanMingguIni(), 0, ',', '.')
            . ' • Bulan ini Rp ' . number_format($this->pendapatanBulanIni(), 0, ',', '.');
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getMaxHeight(): string
    {
        return '330px';
    }

    protected function getPollingInterval(): ?string
    {
        return '60s';
    }

    private function pendapatanHariIni(): int
    {
        return (int) Pembayaran::where('status_pembayaran', 'lunas')
            ->whereDate('tanggal_bayar', today())
            ->sum('jumlah_bayar');
    }

    private function pendapatanKemarin(): int
    {
        return (int) Pembayaran::where('status_pembayaran', 'lunas')
            ->whereDate('tanggal_bayar', today()->subDay())
            ->sum('jumlah_bayar');
    }

    private function pendapatanMingguIni(): int
    {
        return (int) Pembayaran::where('status_pembayaran', 'lunas')
            ->whereBetween('tanggal_bayar', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->sum('jumlah_bayar');
    }

    private function pendapatanBulanIni(): int
    {
        return (int) Pembayaran::where('status_pembayaran', 'lunas')
            ->whereMonth('tanggal_bayar', now()->month)
            ->whereYear('tanggal_bayar', now()->year)
            ->sum('jumlah_bayar');
    }
}