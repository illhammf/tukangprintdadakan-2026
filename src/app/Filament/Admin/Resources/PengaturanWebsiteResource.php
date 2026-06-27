<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PengaturanBookingResource\Pages;
use App\Models\PengaturanBooking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PengaturanBookingResource extends Resource
{
    protected static ?string $model = PengaturanBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'Pengaturan Booking';

    protected static ?string $modelLabel = 'Pengaturan Booking';

    protected static ?string $pluralModelLabel = 'Pengaturan Booking';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Aturan Utama Booking')
                    ->description('Atur aturan dasar pemesanan seperti H-1, jam batas booking, dan hari tutup.')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([
                        Forms\Components\TextInput::make('nama_pengaturan')
                            ->label('Nama Pengaturan')
                            ->required()
                            ->maxLength(255)
                            ->default('Default Booking'),

                        Forms\Components\TimePicker::make('batas_jam_booking')
                            ->label('Batas Jam Booking')
                            ->seconds(false)
                            ->required()
                            ->default('22:00'),

                        Forms\Components\Toggle::make('wajib_h_minus_satu')
                            ->label('Wajib H-1')
                            ->helperText('Jika aktif, pelanggan harus memesan minimal satu hari sebelum pengambilan.')
                            ->default(true)
                            ->required(),

                        Forms\Components\Toggle::make('tutup_sabtu')
                            ->label('Tutup Sabtu')
                            ->default(false)
                            ->required(),

                        Forms\Components\Toggle::make('tutup_minggu')
                            ->label('Tutup Minggu')
                            ->default(true)
                            ->required(),

                        Forms\Components\Toggle::make('tutup_tanggal_merah')
                            ->label('Tutup Tanggal Merah')
                            ->helperText('Jika aktif, sistem akan menolak booking pada data Hari Libur yang aktif.')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Batasan Pesanan')
                    ->description('Atur batas jumlah lembar atau jadwal agar pesanan tetap terkendali.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\TextInput::make('maksimal_lembar_per_hari')
                            ->label('Maksimal Lembar per Hari')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('Contoh: 500'),

                        Forms\Components\TextInput::make('maksimal_lembar_per_order')
                            ->label('Maksimal Lembar per Order')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('Contoh: 100'),

                        Forms\Components\TextInput::make('maksimal_jadwal_belajar_per_jam')
                            ->label('Maksimal Jadwal Belajar per Jam')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('Contoh: 1'),

                        Forms\Components\TextInput::make('minimal_hari_rapihin_tugas')
                            ->label('Minimal Hari Rapihin Tugas')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('Contoh: 2'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Biaya Tambahan')
                    ->description('Atur biaya tambahan yang dapat masuk ke estimasi biaya pesanan.')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Forms\Components\TextInput::make('biaya_jilid')
                            ->label('Biaya Jilid')
                            ->prefix('Rp')
                            ->numeric()
                            ->required()
                            ->default(0),

                        Forms\Components\TextInput::make('biaya_laminating')
                            ->label('Biaya Laminating')
                            ->prefix('Rp')
                            ->numeric()
                            ->required()
                            ->default(0),

                        Forms\Components\TextInput::make('biaya_prioritas')
                            ->label('Biaya Prioritas')
                            ->prefix('Rp')
                            ->numeric()
                            ->required()
                            ->default(0),

                        Forms\Components\TextInput::make('ongkir_kampus')
                            ->label('Ongkir Area Kampus')
                            ->prefix('Rp')
                            ->numeric()
                            ->required()
                            ->default(0),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Aturan Pembayaran dan Pengiriman')
                    ->description('Atur kewajiban bukti pembayaran serta konfirmasi lokasi pengiriman.')
                    ->icon('heroicon-o-truck')
                    ->schema([
                        Forms\Components\Toggle::make('aktifkan_order_prioritas')
                            ->label('Aktifkan Order Prioritas')
                            ->helperText('Jika aktif, pelanggan dapat memilih pengerjaan prioritas.')
                            ->default(false)
                            ->required(),

                        Forms\Components\Toggle::make('wajib_upload_bukti_online')
                            ->label('Wajib Upload Bukti Online')
                            ->helperText('Jika aktif, pembayaran online wajib mengunggah bukti transfer.')
                            ->default(false)
                            ->required(),

                        Forms\Components\Toggle::make('lokasi_luar_kampus_perlu_konfirmasi')
                            ->label('Lokasi Luar Kampus Perlu Konfirmasi')
                            ->default(true)
                            ->required(),

                        Forms\Components\Toggle::make('ojek_online_perlu_konfirmasi')
                            ->label('Ojek Online Perlu Konfirmasi')
                            ->default(true)
                            ->required(),

                        Forms\Components\Textarea::make('catatan_booking')
                            ->label('Catatan Booking')
                            ->placeholder('Contoh: Pesanan wajib dilakukan H-1 sebelum pengambilan.')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('nama_pengaturan')
                    ->label('Nama Pengaturan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (PengaturanBooking $record): ?string => $record->catatan_booking),

                Tables\Columns\IconColumn::make('wajib_h_minus_satu')
                    ->label('H-1')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('batas_jam_booking')
                    ->label('Batas Jam')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\IconColumn::make('tutup_sabtu')
                    ->label('Tutup Sabtu')
                    ->boolean(),

                Tables\Columns\IconColumn::make('tutup_minggu')
                    ->label('Tutup Minggu')
                    ->boolean(),

                Tables\Columns\IconColumn::make('tutup_tanggal_merah')
                    ->label('Tanggal Merah')
                    ->boolean(),

                Tables\Columns\TextColumn::make('biaya_jilid')
                    ->label('Jilid')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('biaya_laminating')
                    ->label('Laminating')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ongkir_kampus')
                    ->label('Ongkir')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\IconColumn::make('aktifkan_order_prioritas')
                    ->label('Prioritas')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('wajib_h_minus_satu')
                    ->label('Wajib H-1')
                    ->placeholder('Semua')
                    ->trueLabel('Wajib')
                    ->falseLabel('Tidak Wajib'),

                Tables\Filters\TernaryFilter::make('aktifkan_order_prioritas')
                    ->label('Order Prioritas')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
                Tables\Actions\EditAction::make()
                    ->label('Edit'),
            ])
            ->bulkActions([])
            ->emptyStateHeading('Belum ada pengaturan booking')
            ->emptyStateDescription('Tambahkan pengaturan booking untuk mengatur aturan pemesanan layanan.')
            ->emptyStateIcon('heroicon-o-calendar-days');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return PengaturanBooking::count() === 0;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengaturanBookings::route('/'),
            'create' => Pages\CreatePengaturanBooking::route('/create'),
            'edit' => Pages\EditPengaturanBooking::route('/{record}/edit'),
        ];
    }
}