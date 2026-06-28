<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PesananResource\Pages;
use App\Models\Pesanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Admin\Resources\PesananResource\RelationManagers\DetailPesanansRelationManager;
use App\Filament\Admin\Resources\PesananResource\RelationManagers\PembayaranRelationManager;
use App\Filament\Admin\Resources\PesananResource\RelationManagers\PengirimanRelationManager;
use App\Filament\Admin\Resources\PesananResource\RelationManagers\RiwayatStatusPesanansRelationManager;
use Illuminate\Support\Str;

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
        $count = Pesanan::where('status_pesanan', 'menunggu_verifikasi')->count();

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
                Forms\Components\Section::make('Informasi Pelanggan')
                    ->description('Data pelanggan yang membuat pesanan.')
                    ->icon('heroicon-s-user')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Akun Pelanggan')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload(),

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
                    ->icon('heroicon-s-document-text')
                    ->schema([
                        Forms\Components\TextInput::make('kode_pesanan')
                            ->label('Kode Pesanan')
                            ->default(fn () => 'TPD-' . now()->format('Ymd') . '-' . str_pad(\App\Models\Pesanan::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT))
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\DatePicker::make('tanggal_pesan')
                            ->label('Tanggal Pesan')
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->required()
                            ->default(now()),

                        Forms\Components\DatePicker::make('tanggal_pengambilan')
                            ->label('Tanggal Pengambilan')
                            ->native(false)
                            ->displayFormat('d M Y'),

                        Forms\Components\TimePicker::make('jam_pengambilan')
                            ->label('Jam Pengambilan')
                            ->seconds(false),

                        Forms\Components\Select::make('lokasi_pengambilan')
                            ->label('Lokasi Pengambilan')
                            ->options([
                                'Kampus UEU Tangerang' => 'Kampus UEU Tangerang',
                                'Diantar' => 'Diantar',
                                'Ojek Online' => 'Ojek Online',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->searchable(),

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
                            ->required(),

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

                Forms\Components\Section::make('Ringkasan Biaya')
                    ->description('Total biaya pesanan berdasarkan layanan dan biaya tambahan.')
                    ->icon('heroicon-s-banknotes')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->prefix('Rp')
                            ->numeric()
                            ->required()
                            ->default(0),

                        Forms\Components\TextInput::make('biaya_tambahan')
                            ->label('Biaya Tambahan')
                            ->prefix('Rp')
                            ->numeric()
                            ->required()
                            ->default(0),

                        Forms\Components\TextInput::make('biaya_pengiriman')
                            ->label('Biaya Pengiriman')
                            ->prefix('Rp')
                            ->numeric()
                            ->required()
                            ->default(0),

                        Forms\Components\TextInput::make('total_harga')
                            ->label('Total Harga')
                            ->prefix('Rp')
                            ->numeric()
                            ->required()
                            ->default(0),
                    ])
                    ->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('kode_pesanan')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('nama_pelanggan')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Pesanan $record): ?string => $record->nomor_whatsapp),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Akun')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('tanggal_pengambilan')
                    ->label('Ambil')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jam_pengambilan')
                    ->label('Jam'),

                Tables\Columns\TextColumn::make('lokasi_pengambilan')
                    ->label('Lokasi')
                    ->searchable()
                    ->toggleable(),

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
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pesanan')
                    ->label('Status Pesanan')
                    ->options([
                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                        'diproses' => 'Diproses',
                        'siap_diambil' => 'Siap Diambil',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ]),

                Tables\Filters\SelectFilter::make('lokasi_pengambilan')
                    ->label('Lokasi Pengambilan')
                    ->options([
                        'Kampus UEU Tangerang' => 'Kampus UEU Tangerang',
                        'Diantar' => 'Diantar',
                        'Ojek Online' => 'Ojek Online',
                        'Lainnya' => 'Lainnya',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),

                Tables\Actions\Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Pesanan $record): bool => $record->status_pesanan === 'menunggu_verifikasi')
                    ->action(fn (Pesanan $record) => $record->update([
                        'status_pesanan' => 'diproses',
                    ])),

                Tables\Actions\Action::make('siap_diambil')
                    ->label('Siap Diambil')
                    ->icon('heroicon-o-archive-box')
                    ->color('info')
                    ->visible(fn (Pesanan $record): bool => $record->status_pesanan === 'diproses')
                    ->action(fn (Pesanan $record) => $record->update([
                        'status_pesanan' => 'siap_diambil',
                    ])),

                Tables\Actions\Action::make('selesai')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-badge')
                    ->color('gray')
                    ->visible(fn (Pesanan $record): bool => $record->status_pesanan === 'siap_diambil')
                    ->action(fn (Pesanan $record) => $record->update([
                        'status_pesanan' => 'selesai',
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
            ->emptyStateHeading('Belum ada pesanan')
            ->emptyStateDescription('Pesanan pelanggan akan muncul di sini setelah dibuat.')
            ->emptyStateIcon('heroicon-s-shopping-bag');
    }

    public static function getRelations(): array
    {
        return [
            DetailPesanansRelationManager::class,
            PembayaranRelationManager::class,
            PengirimanRelationManager::class,
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

    protected static function booted(): void
    {
        static::creating(function (Pesanan $pesanan) {
            if (blank($pesanan->kode_pesanan)) {
                $tanggal = now()->format('Ymd');

                $nomorUrut = Pesanan::whereDate('created_at', today())->count() + 1;

                $pesanan->kode_pesanan = 'TPD-' . $tanggal . '-' . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);
            }

            if (blank($pesanan->tanggal_pesan)) {
                $pesanan->tanggal_pesan = now();
            }
        });

        static::created(function (Pesanan $pesanan) {
            $pesanan->riwayatStatusPesanans()->create([
                'status' => $pesanan->status_pesanan,
                'catatan' => 'Pesanan berhasil dibuat.',
                'waktu_status' => now(),
            ]);
        });

        static::updated(function (Pesanan $pesanan) {
            if ($pesanan->wasChanged('status_pesanan')) {
                $pesanan->riwayatStatusPesanans()->create([
                    'status' => $pesanan->status_pesanan,
                    'catatan' => 'Status pesanan berubah menjadi ' . str_replace('_', ' ', $pesanan->status_pesanan) . '.',
                    'waktu_status' => now(),
                ]);
            }
        });
    }
}