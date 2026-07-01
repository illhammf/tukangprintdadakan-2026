<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KategoriLayananResource\Pages;
use App\Models\KategoriLayanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class KategoriLayananResource extends Resource
{
    protected static ?string $model = KategoriLayanan::class;

    protected static ?string $navigationIcon = 'heroicon-s-squares-2x2';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Kategori Layanan';

    protected static ?string $modelLabel = 'Kategori Layanan';

    protected static ?string $pluralModelLabel = 'Kategori Layanan';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kategori')
                    ->description('Kelola kategori utama layanan seperti Print, Fotokopi, Jilid, dan Laminating.')
                    ->icon('heroicon-o-squares-2x2')
                    ->schema([
                        Forms\Components\TextInput::make('nama_kategori')
                            ->label('Nama Kategori')
                            ->placeholder('Contoh: Print Dokumen')
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
                            ->placeholder('print-dokumen')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('gambar')
                            ->label('Gambar Kategori')
                            ->image()
                            ->directory('kategori-layanan')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->placeholder('Tuliskan deskripsi singkat kategori layanan.')
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('status')
                            ->label('Aktif')
                            ->helperText('Jika aktif, kategori dapat ditampilkan pada website.')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),
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

                Tables\Columns\TextColumn::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (KategoriLayanan $record): ?string => $record->deskripsi),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('layanans_count')
                    ->label('Jumlah Layanan')
                    ->counts('layanans')
                    ->badge()
                    ->color('info')
                    ->sortable(),

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
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status Kategori')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),

                Tables\Actions\EditAction::make()
                    ->label('Edit'),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->visible(fn (KategoriLayanan $record): bool => $record->layanans()->count() === 0)
                    ->requiresConfirmation(),
            ])
            ->bulkActions([])
            ->emptyStateHeading('Belum ada kategori layanan')
            ->emptyStateDescription('Tambahkan kategori layanan pertama untuk mengelompokkan layanan print.')
            ->emptyStateIcon('heroicon-o-squares-2x2');
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
            'index' => Pages\ListKategoriLayanans::route('/'),
            'create' => Pages\CreateKategoriLayanan::route('/create'),
            'edit' => Pages\EditKategoriLayanan::route('/{record}/edit'),
        ];
    }
}