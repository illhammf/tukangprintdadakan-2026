<?php

namespace App\Filament\Admin\Widgets;

use App\Models\KontakMasuk;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Filament\Widgets\Widget;

class QuickActionDashboard extends Widget
{
    protected static string $view = 'filament.admin.widgets.quick-action-dashboard';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'userName' => auth()->user()?->name ?? 'Admin',
            'pesananHariIni' => Pesanan::whereDate('created_at', today())->count(),
            'pengambilanBesok' => Pesanan::whereDate('tanggal_pengambilan', today()->addDay())
                ->whereNotIn('status_pesanan', ['selesai', 'dibatalkan'])
                ->count(),
            'pembayaranMenunggu' => Pembayaran::where('status_pembayaran', 'menunggu_verifikasi')->count(),
            'pesanBaru' => KontakMasuk::where('status_pesan', 'baru')->count(),
        ];
    }
}