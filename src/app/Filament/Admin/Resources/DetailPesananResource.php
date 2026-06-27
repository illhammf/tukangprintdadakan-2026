<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DetailPesananResource\Pages;
use App\Models\DetailPesanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DetailPesananResource extends Resource
{
    protected static ?string $model = DetailPesanan::class;

    protected static ?string $navigationIcon = 'heroicon-s-document-duplicate';

    protected static ?string $navigationGroup = 'Pemesanan';

    protected static ?string $navigationLabel = 'Detail Pesanan';

    protected static ?string $modelLabel = 'Detail Pesanan';

    protected static ?string $pluralModelLabel = 'Detail Pesanan';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Relasi Pesanan')
                    ->description('Pilih pesanan dan layanan yang diproses.')
                    ->icon('heroicon-o-link')
                    ->schema([
                        Forms\Components\Select::make('pesanan_id')
                            ->label('Kode Pesanan')
                            ->relationship('pesanan', 'kode_pesanan')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('layanan_id')
                            ->label('Layanan')
                            ->relationship('layanan', 'nama_layanan')
                            ->searchable()
                            ->preload(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('File Pesanan')
                    ->description('Kelola file yang dikirim pelanggan untuk dicetak.')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->schema([
                        Forms\Components\TextInput::make('nama_file')
                            ->label('Nama File')
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('file_path')
                            ->label('Upload File')
                            ->directory('pesanan')
                            ->preserveFilenames()
                            ->downloadable()
                            ->openable()
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-powerpoint',
                                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                'image/jpeg',
                                'image/png',
                            ])
                            ->maxSize(20480)
                            ->helperText('Format: PDF, DOC, DOCX, PPT, PPTX, JPG, PNG. Maksimal 20 MB.'),

                        Forms\Components\Textarea::make('catatan_detail')
                            ->label('Catatan Detail')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Spesifikasi Cetak')
                    ->description('Atur jenis print, ukuran kertas, jumlah halaman, dan jumlah copy.')
                    ->icon('heroicon-o-printer')
                    ->schema([
                        Forms\Components\Select::make('jenis_print')
                            ->label('Jenis Print')
                            ->options([
                                'hitam_putih' => 'Hitam Putih',
                                'warna' => 'Warna',
                            ])
                            ->native(false),

                        Forms\Components\Select::make('ukuran_kertas')
                            ->label('Ukuran Kertas')
                            ->options([
                                'A4' => 'A4',
                                'F4' => 'F4',
                            ])
                            ->default('A4')
                            ->required(),

                        Forms\Components\TextInput::make('jumlah_halaman')
                            ->label('Jumlah Halaman')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->default(1),

                        Forms\Components\TextInput::make('jumlah_copy')
                            ->label('Jumlah Copy')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->default(1),

                        Forms\Components\Toggle::make('pakai_jilid')
                            ->label('Pakai Jilid')
                            ->default(false)
                            ->required(),

                        Forms\Components\Toggle::make('pakai_laminating')
                            ->label('Pakai Laminating')
                            ->default(false)
                            ->required(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Biaya Detail')
                    ->description('Atur harga satuan dan subtotal untuk detail pesanan.')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Forms\Components\TextInput::make('harga_satuan')
                            ->label('Harga Satuan')
                            ->prefix('Rp')
                            ->numeric()
                            ->required()
                            ->default(0),

                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->prefix('Rp')
                            ->numeric()
                            ->required()
                            ->default(0),
                    ])
                    ->columns(2),
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

                Tables\Columns\TextColumn::make('layanan.nama_layanan')
                    ->label('Layanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('nama_file')
                    ->label('File')
                    ->searchable()
                    ->limit(30)
                    ->icon('heroicon-o-document'),

                Tables\Columns\TextColumn::make('jenis_print')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'hitam_putih' => 'Hitam Putih',
                        'warna' => 'Warna',
                        default => '-',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'hitam_putih' => 'gray',
                        'warna' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('ukuran_kertas')
                    ->label('Kertas')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('jumlah_halaman')
                    ->label('Halaman')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jumlah_copy')
                    ->label('Copy')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('harga_satuan')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\IconColumn::make('pakai_jilid')
                    ->label('Jilid')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\IconColumn::make('pakai_laminating')
                    ->label('Laminating')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_print')
                    ->label('Jenis Print')
                    ->options([
                        'hitam_putih' => 'Hitam Putih',
                        'warna' => 'Warna',
                    ]),

                Tables\Filters\SelectFilter::make('ukuran_kertas')
                    ->label('Ukuran Kertas')
                    ->options([
                        'A4' => 'A4',
                        'F4' => 'F4',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
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
            ->emptyStateHeading('Belum ada detail pesanan')
            ->emptyStateDescription('Detail layanan dan file pesanan pelanggan akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-document-duplicate');
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
            'index' => Pages\ListDetailPesanans::route('/'),
            'create' => Pages\CreateDetailPesanan::route('/create'),
            'edit' => Pages\EditDetailPesanan::route('/{record}/edit'),
        ];
    }
}