<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PembayaranResource\Pages;
use App\Models\Pembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-s-credit-card';

    protected static ?string $navigationGroup = 'Pemesanan';

    protected static ?string $navigationLabel = 'Pembayaran';

    protected static ?string $modelLabel = 'Pembayaran';

    protected static ?string $pluralModelLabel = 'Pembayaran';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        $count = Pembayaran::where('status_pembayaran', 'menunggu_verifikasi')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Relasi Pesanan')
                    ->description('Pilih pesanan yang memiliki data pembayaran.')
                    ->icon('heroicon-o-shopping-bag')
                    ->schema([
                        Forms\Components\Select::make('pesanan_id')
                            ->label('Kode Pesanan')
                            ->relationship('pesanan', 'kode_pesanan')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),

                Forms\Components\Section::make('Informasi Pembayaran')
                    ->description('Kelola metode pembayaran dan jumlah bayar pelanggan.')
                    ->icon('heroicon-o-credit-card')
                    ->schema([
                        Forms\Components\Select::make('metode_pembayaran')
                            ->label('Metode Pembayaran')
                            ->options([
                                'cash' => 'Cash',
                                'transfer' => 'Transfer Bank',
                            ])
                            ->native(false)
                            ->live()
                            ->required(),

                        Forms\Components\Select::make('channel_pembayaran')
                            ->label('Channel Pembayaran')
                            ->options([
                                'cash' => 'Cash',
                                'dana' => 'DANA',
                                'bri' => 'BRI',
                                'qris' => 'QRIS',
                                'lainnya' => 'Lainnya',
                            ])
                            ->searchable(),

                        Forms\Components\TextInput::make('jumlah_bayar')
                            ->label('Jumlah Bayar')
                            ->prefix('Rp')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->default(0),

                        Forms\Components\Select::make('status_pembayaran')
                            ->label('Status Pembayaran')
                            ->options([
                                'belum_bayar' => 'Belum Bayar',
                                'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                'lunas' => 'Lunas',
                                'ditolak' => 'Ditolak',
                            ])
                            ->default('belum_bayar')
                            ->required(),

                        Forms\Components\DateTimePicker::make('tanggal_bayar')
                            ->label('Tanggal Bayar')
                            ->native(false)
                            ->displayFormat('d M Y H:i'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Bukti Pembayaran')
                    ->description('Unggah bukti pembayaran jika pelanggan menggunakan metode transfer atau online.')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->schema([
                        Forms\Components\FileUpload::make('bukti_pembayaran')
                            ->label('Bukti Pembayaran')
                            ->image()
                            ->directory('bukti-pembayaran')
                            ->imageEditor()
                            ->downloadable()
                            ->openable()
                            ->maxSize(2048)
                            ->helperText('Format gambar. Maksimal 2 MB.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('pesanan.kode_pesanan')
                    ->label('Kode Pesanan')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->copyable(),

                Tables\Columns\TextColumn::make('pesanan.nama_pelanggan')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('metode_pembayaran')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'cash' => 'Cash',
                        'transfer' => 'Transfer',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'gray',
                        'transfer' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('channel_pembayaran')
                    ->label('Channel')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn (?string $state): string => $state ? strtoupper($state) : '-'),

                Tables\Columns\TextColumn::make('jumlah_bayar')
                    ->label('Jumlah Bayar')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('bukti_pembayaran')
                    ->label('Bukti')
                    ->square()
                    ->defaultImageUrl(url('/images/placeholder.png')),

                Tables\Columns\TextColumn::make('status_pembayaran')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'belum_bayar' => 'Belum Bayar',
                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                        'lunas' => 'Lunas',
                        'ditolak' => 'Ditolak',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'belum_bayar' => 'gray',
                        'menunggu_verifikasi' => 'warning',
                        'lunas' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('tanggal_bayar')
                    ->label('Tanggal Bayar')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->options([
                        'cash' => 'Cash',
                        'transfer' => 'Transfer',
                    ]),

                Tables\Filters\SelectFilter::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->options([
                        'belum_bayar' => 'Belum Bayar',
                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                        'lunas' => 'Lunas',
                        'ditolak' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),

                Tables\Actions\Action::make('tandai_lunas')
                    ->label('Tandai Lunas')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Pembayaran $record): bool => $record->status_pembayaran !== 'lunas')
                    ->action(fn (Pembayaran $record) => $record->update([
                        'status_pembayaran' => 'lunas',
                        'tanggal_bayar' => $record->tanggal_bayar ?? now(),
                        'jumlah_bayar' => $record->jumlah_bayar > 0
                            ? $record->jumlah_bayar
                            : ($record->pesanan?->total_harga ?? 0),
                    ])),

                Tables\Actions\Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Pembayaran $record): bool => $record->status_pembayaran === 'menunggu_verifikasi')
                    ->requiresConfirmation()
                    ->action(fn (Pembayaran $record) => $record->update([
                        'status_pembayaran' => 'ditolak',
                    ])),

                Tables\Actions\EditAction::make()
                    ->label('Edit'),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ])
            ->emptyStateHeading('Belum ada data pembayaran')
            ->emptyStateDescription('Data pembayaran pelanggan akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-credit-card');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembayarans::route('/'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }
}