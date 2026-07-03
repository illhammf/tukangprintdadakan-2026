<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\PesananResource;
use App\Models\Pesanan;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PesananTerbaru extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pesanan::query()
                    ->with(['pembayaran'])
                    ->withCount('detailPesanans')
                    ->latest()
                    ->limit(8)
            )
            ->heading('🧾 Pesanan Terbaru')
            ->description('Pantau pesanan terbaru yang masuk ke sistem Tukang Print Dadakan.')
            ->columns([
                Tables\Columns\TextColumn::make('kode_pesanan')
                    ->label('Kode')
                    ->badge()
                    ->copyable()
                    ->color('gray')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_pelanggan')
                    ->label('Pelanggan')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Pesanan $record): ?string => $record->nomor_whatsapp),

                Tables\Columns\TextColumn::make('detail_pesanans_count')
                    ->label('Item')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('tanggal_pengambilan')
                    ->label('Ambil')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('pembayaran.status_pembayaran')
                    ->label('Bayar')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'belum_bayar' => 'Belum Bayar',
                        'menunggu_verifikasi' => 'Menunggu',
                        'lunas' => 'Lunas',
                        'ditolak' => 'Ditolak',
                        default => '-',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'belum_bayar' => 'gray',
                        'menunggu_verifikasi' => 'warning',
                        'lunas' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    }),

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

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Masuk')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Pesanan $record): bool => $record->status_pesanan === 'menunggu_verifikasi')
                    ->action(function (Pesanan $record): void {
                        $record->ubahStatus(
                            'diproses',
                            'Pesanan telah diverifikasi oleh admin dan mulai diproses.'
                        );

                        Notification::make()
                            ->title('Pesanan berhasil diverifikasi')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Pesanan $record): string => PesananResource::getUrl('edit', [
                        'record' => $record,
                    ])),
            ])
            ->paginated(false)
            ->emptyStateHeading('Belum ada pesanan terbaru')
            ->emptyStateDescription('Pesanan pelanggan yang masuk akan tampil di bagian ini.')
            ->emptyStateIcon('heroicon-o-shopping-bag');
    }
}