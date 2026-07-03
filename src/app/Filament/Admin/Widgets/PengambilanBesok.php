<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\PesananResource;
use App\Models\Pesanan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PengambilanBesok extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $now = now(config('app.timezone', 'Asia/Jakarta')); // Untuk memastikan waktu sesuai dengan zona waktu yang diinginkan

        return $table
            ->query(
                Pesanan::query()
                    ->whereDate('tanggal_pengambilan', $now->copy()->addDay()->toDateString())
                    ->whereNotIn('status_pesanan', ['selesai', 'dibatalkan'])
                    ->orderBy('jam_pengambilan')
            )
            ->heading('📅 Pengambilan Besok')
            ->description('Daftar pesanan yang perlu disiapkan untuk pengambilan besok.')
            ->columns([
                Tables\Columns\TextColumn::make('kode_pesanan')
                    ->label('Kode')
                    ->badge()
                    ->color('gray')
                    ->copyable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_pelanggan')
                    ->label('Pelanggan')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Pesanan $record): ?string => $record->nomor_whatsapp),

                Tables\Columns\TextColumn::make('jam_pengambilan')
                    ->label('Jam')
                    ->time('H:i')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('lokasi_pengambilan')
                    ->label('Lokasi')
                    ->limit(30)
                    ->wrap()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status_pesanan')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                        'diproses' => 'Diproses',
                        'siap_diambil' => 'Siap Diambil',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu_verifikasi' => 'warning',
                        'diproses' => 'info',
                        'siap_diambil' => 'success',
                        'selesai' => 'gray',
                        'dibatalkan' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Pesanan $record): string => PesananResource::getUrl('edit', [
                        'record' => $record,
                    ])),
            ])
            ->paginated(false)
            ->emptyStateHeading('Tidak ada pengambilan besok')
            ->emptyStateDescription('Jika ada pesanan untuk besok, daftar pesanan akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-calendar-days');
    }
}