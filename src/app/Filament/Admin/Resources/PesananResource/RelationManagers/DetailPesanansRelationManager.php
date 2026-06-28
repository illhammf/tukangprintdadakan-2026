<?php

namespace App\Filament\Admin\Resources\PesananResource\RelationManagers;

use App\Models\Layanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DetailPesanansRelationManager extends RelationManager
{
    protected static string $relationship = 'detailPesanans';

    protected static ?string $title = 'Detail Pesanan';

    protected static ?string $modelLabel = 'Detail Pesanan';

    protected static ?string $pluralModelLabel = 'Detail Pesanan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Layanan dan File')
                    ->schema([
                        Forms\Components\Select::make('layanan_id')
                            ->label('Layanan')
                            ->options(Layanan::where('status', true)->pluck('nama_layanan', 'id'))
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('nama_file')
                            ->label('Nama File')
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('file_path')
                            ->label('File Pesanan')
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
                            ->helperText('Format: PDF, DOC, DOCX, PPT, PPTX, JPG, PNG. Maksimal 20 MB.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Spesifikasi Cetak')
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
                            ->default(1)
                            ->required(),

                        Forms\Components\TextInput::make('jumlah_copy')
                            ->label('Jumlah Copy')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required(),

                        Forms\Components\Toggle::make('pakai_jilid')
                            ->label('Pakai Jilid')
                            ->default(false),

                        Forms\Components\Toggle::make('pakai_laminating')
                            ->label('Pakai Laminating')
                            ->default(false),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Biaya')
                    ->schema([
                        Forms\Components\TextInput::make('harga_satuan')
                            ->label('Harga Satuan')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Forms\Components\Textarea::make('catatan_detail')
                            ->label('Catatan Detail')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_file')
            ->columns([
                Tables\Columns\TextColumn::make('layanan.nama_layanan')
                    ->label('Layanan')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_file')
                    ->label('File')
                    ->icon('heroicon-o-document')
                    ->limit(30)
                    ->searchable()
                    ->placeholder('-'),

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
                    ->numeric(),

                Tables\Columns\TextColumn::make('jumlah_copy')
                    ->label('Copy')
                    ->numeric(),

                Tables\Columns\TextColumn::make('harga_satuan')
                    ->label('Harga')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR')
                    ->weight('bold'),

                Tables\Columns\IconColumn::make('pakai_jilid')
                    ->label('Jilid')
                    ->boolean(),

                Tables\Columns\IconColumn::make('pakai_laminating')
                    ->label('Laminating')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_print')
                    ->label('Jenis Print')
                    ->options([
                        'hitam_putih' => 'Hitam Putih',
                        'warna' => 'Warna',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Detail'),
            ])
            ->actions([
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
            ->emptyStateDescription('Tambahkan layanan, file, dan spesifikasi cetak untuk pesanan ini.')
            ->emptyStateIcon('heroicon-o-document-duplicate');
    }
}