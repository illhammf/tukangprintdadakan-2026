<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LayananResource\Pages;
use App\Models\Layanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class LayananResource extends Resource
{
    protected static ?string $model = Layanan::class;

    protected static ?string $navigationIcon = 'heroicon-s-printer';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Layanan';

    protected static ?string $modelLabel = 'Layanan';

    protected static ?string $pluralModelLabel = 'Layanan';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Layanan')
                    ->description('Kelola layanan yang akan ditampilkan pada website Tukang Print Dadakan.')
                    ->icon('heroicon-o-printer')
                    ->schema([
                        Forms\Components\Select::make('kategori_layanan_id')
                            ->label('Kategori Layanan')
                            ->relationship('kategoriLayanan', 'nama_kategori')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('nama_layanan')
                            ->label('Nama Layanan')
                            ->placeholder('Contoh: Print Hitam Putih')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->placeholder('print-hitam-putih')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->placeholder('Tuliskan deskripsi singkat layanan.')
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('gambar')
                            ->label('Gambar Layanan')
                            ->image()
                            ->directory('layanan')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Harga dan Aturan Layanan')
                    ->description('Atur harga dasar, satuan, dan ketentuan layanan.')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Forms\Components\TextInput::make('harga_dasar')
                            ->label('Harga Dasar')
                            ->prefix('Rp')
                            ->numeric()
                            ->required()
                            ->default(0),

                        Forms\Components\Select::make('satuan')
                            ->label('Satuan')
                            ->options([
                                'lembar' => 'Lembar',
                                'halaman' => 'Halaman',
                                'jilid' => 'Jilid',
                                'layanan' => 'Layanan',
                            ])
                            ->required()
                            ->default('layanan'),

                        Forms\Components\Toggle::make('butuh_upload_file')
                            ->label('Butuh Upload File')
                            ->helperText('Aktifkan jika pelanggan wajib mengunggah file untuk layanan ini.')
                            ->default(true)
                            ->required(),

                        Forms\Components\Toggle::make('bisa_online')
                            ->label('Bisa Dipesan Online')
                            ->helperText('Jika aktif, layanan dapat dipesan melalui website.')
                            ->default(true)
                            ->required(),

                        Forms\Components\Toggle::make('status')
                            ->label('Aktif')
                            ->helperText('Jika aktif, layanan akan tampil pada website.')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->square()
                    ->defaultImageUrl(url('/images/placeholder.png')),

                Tables\Columns\TextColumn::make('nama_layanan')
                    ->label('Nama Layanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Layanan $record): ?string => $record->deskripsi),

                Tables\Columns\TextColumn::make('kategoriLayanan.nama_kategori')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('harga_dasar')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('satuan')
                    ->label('Satuan')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\IconColumn::make('butuh_upload_file')
                    ->label('Upload')
                    ->boolean()
                    ->trueIcon('heroicon-o-arrow-up-tray')
                    ->falseIcon('heroicon-o-minus-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\IconColumn::make('bisa_online')
                    ->label('Online')
                    ->boolean()
                    ->trueIcon('heroicon-o-globe-alt')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori_layanan_id')
                    ->label('Kategori')
                    ->relationship('kategoriLayanan', 'nama_kategori')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status Layanan')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),

                Tables\Filters\TernaryFilter::make('bisa_online')
                    ->label('Pemesanan Online')
                    ->placeholder('Semua')
                    ->trueLabel('Bisa Online')
                    ->falseLabel('Tidak Online'),

                Tables\Filters\TernaryFilter::make('butuh_upload_file')
                    ->label('Upload File')
                    ->placeholder('Semua')
                    ->trueLabel('Butuh Upload')
                    ->falseLabel('Tidak Butuh Upload'),
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
            ->emptyStateHeading('Belum ada layanan')
            ->emptyStateDescription('Tambahkan layanan seperti Print Hitam Putih, Print Warna, Fotokopi, Jilid, atau Laminating.')
            ->emptyStateIcon('heroicon-o-printer');
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
            'index' => Pages\ListLayanans::route('/'),
            'create' => Pages\CreateLayanan::route('/create'),
            'edit' => Pages\EditLayanan::route('/{record}/edit'),
        ];
    }
}