<?php

namespace App\Filament\Admin\Widgets;

use App\Models\DetailPesanan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class LayananTerlaris extends ChartWidget
{
    protected static ?string $heading = '🏆 Layanan Terlaris';

    protected static ?string $description = 'Top 5 layanan yang paling sering dipesan pelanggan.';

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = '1/2';

    protected function getData(): array
    {
        $layanan = DetailPesanan::query()
            ->join('layanans', 'detail_pesanans.layanan_id', '=', 'layanans.id')
            ->select(
                'layanans.nama_layanan',
                DB::raw('COUNT(detail_pesanans.id) as total')
            )
            ->groupBy('layanans.nama_layanan')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pesanan',
                    'data' => $layanan->pluck('total')->toArray(),
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.80)',
                        'rgba(34, 197, 94, 0.80)',
                        'rgba(245, 158, 11, 0.80)',
                        'rgba(168, 85, 247, 0.80)',
                        'rgba(239, 68, 68, 0.80)',
                    ],
                    'borderColor' => [
                        'rgb(37, 99, 235)',
                        'rgb(22, 163, 74)',
                        'rgb(217, 119, 6)',
                        'rgb(147, 51, 234)',
                        'rgb(220, 38, 38)',
                    ],
                    'borderWidth' => 2,
                    'hoverOffset' => 12,
                ],
            ],
            'labels' => $layanan->pluck('nama_layanan')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getMaxHeight(): string
    {
        return '320px';
    }

    protected function getPollingInterval(): ?string
    {
        return '60s';
    }
}