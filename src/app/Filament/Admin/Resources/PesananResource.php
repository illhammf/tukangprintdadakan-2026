<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PesananResource\Pages;
use App\Filament\Admin\Resources\PesananResource\RelationManagers\DetailPesanansRelationManager;
use App\Filament\Admin\Resources\PesananResource\RelationManagers\PembayaranRelationManager;
use App\Filament\Admin\Resources\PesananResource\RelationManagers\PengirimanRelationManager;
use App\Filament\Admin\Resources\PesananResource\RelationManagers\RiwayatStatusPesanansRelationManager;
use App\Models\Pesanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PesananResource extends Resource
{
    protected static ?string $model = Pesanan::class;

    protected static ?string $navigationIcon = 'heroicon-s-shopping-bag';
    protected static ?string $navigationGroup = 'Pemesanan';
    protected static ?string $navigationLabel = 'Pesanan';
    protected static ?string $modelLabel = 'Pesanan';
    protected static ?string $pluralModelLabel = 'Pesanan';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    private static function hitungTotal(Get $get, Set $set): void
    {
        $subtotal = (float) ($get('subtotal') ?: 0);
        $biayaTambahan = (float) ($get('biaya_tambahan') ?: 0);
        $biayaPengiriman = (float) ($get('biaya_pengiriman') ?: 0);

        $set('total_harga', $subtotal + $biayaTambahan + $biayaPengiriman);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pelanggan')
                    ->description('Data pelanggan yang membuat pesanan.')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Akun Pelanggan')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        Forms\Components\TextInput::make('nama_pelanggan')
                            ->label('Nama Pelanggan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('nomor_whatsapp')
                            ->label('Nomor WhatsApp')
                            ->tel()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Informasi Pesanan')
                    ->description('Data utama pesanan dan jadwal pengambilan.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\TextInput::make('kode_pesanan')
                            ->label('Kode Pesanan')
                            ->default(fn () => 'TPD-' . now()->format('Ymd') . '-' . str_pad(Pesanan::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT))
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\DatePicker::make('tanggal_pesan')
                            ->label('Tanggal Pesan')
                            ->default(today())
                            ->required(),

                        Forms\Components\DatePicker::make('tanggal_pengambilan')
                            ->label('Tanggal Pengambilan')
                            ->minDate(today())
                            ->required(),

                        Forms\Components\TimePicker::make('jam_pengambilan')
                            ->label('Jam Pengambilan')
                            ->seconds(false)
                            ->required(),

                        Forms\Components\Select::make('lokasi_pengambilan')
                            ->label('Lokasi Pengambilan')
                            ->options([
                                'Kampus UEU Tangerang' => 'Kampus UEU Tangerang',
                                'Ojek Online' => 'Ojek Online',
                                'Diantar' => 'Diantar',
                            ])
                            ->native(false)
                            ->required(),

                        Forms\Components\Select::make('status_pesanan')
                            ->label('Status Pesanan')
                            ->options([
                                'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                'diproses' => 'Diproses',
                                'siap_diambil' => 'Siap Diambil',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->default('menunggu_verifikasi')
                            ->native(false)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Status pesanan diubah melalui tombol aksi agar riwayat status tercatat.'),

                        Forms\Components\Textarea::make('detail_lokasi')
                            ->label('Detail Lokasi')
                            ->placeholder('Contoh: COD area kampus, dekat lobby, atau alamat pengantaran.')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan Pesanan')
                            ->placeholder('Catatan tambahan dari pelanggan atau admin.')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('kode_pesanan')
                    ->label('Kode')
                    ->badge()
                    ->copyable()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_pelanggan')
                    ->label('Pelanggan')
                    ->searchable()
                    ->description(fn (Pesanan $record): ?string => $record->nomor_whatsapp),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Akun')
                    ->placeholder('Guest')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_pengambilan')
                    ->label('Ambil')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jam_pengambilan')
                    ->label('Jam')
                    ->time('H:i'),

                Tables\Columns\TextColumn::make('lokasi_pengambilan')
                    ->label('Lokasi')
                    ->limit(25)
                    ->searchable(),

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

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
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
                    ->action(fn (Pesanan $record) => $record->ubahStatus(
                        'diproses',
                        'Pesanan sudah diverifikasi dan masuk tahap diproses.'
                    )),

                Tables\Actions\Action::make('siap_diambil')
                    ->label('Siap Diambil')
                    ->icon('heroicon-o-archive-box')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (Pesanan $record): bool => $record->status_pesanan === 'diproses')
                    ->action(fn (Pesanan $record) => $record->ubahStatus(
                        'siap_diambil',
                        'Pesanan sudah selesai diproses dan siap diambil.'
                    )),

                Tables\Actions\Action::make('selesai')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Pesanan $record): bool => $record->status_pesanan === 'siap_diambil')
                    ->action(fn (Pesanan $record) => $record->ubahStatus(
                        'selesai',
                        'Pesanan sudah diselesaikan dan diserahkan kepada pelanggan.'
                    )),

                Tables\Actions\Action::make('batalkan')
                    ->label('Batalkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Pesanan $record): bool => ! in_array($record->status_pesanan, ['selesai', 'dibatalkan']))
                    ->action(fn (Pesanan $record) => $record->ubahStatus(
                        'dibatalkan',
                        'Pesanan dibatalkan oleh admin.'
                    )),

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
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DetailPesanansRelationManager::class,
            PengirimanRelationManager::class,
            PembayaranRelationManager::class,
            RiwayatStatusPesanansRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPesanans::route('/'),
            'create' => Pages\CreatePesanan::route('/create'),
            'edit' => Pages\EditPesanan::route('/{record}/edit'),
        ];
    }
}