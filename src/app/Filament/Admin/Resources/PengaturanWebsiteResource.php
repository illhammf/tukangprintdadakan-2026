<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PengaturanWebsiteResource\Pages;
use App\Models\PengaturanWebsite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PengaturanWebsiteResource extends Resource
{
    protected static ?string $model = PengaturanWebsite::class;

    protected static ?string $navigationIcon = 'heroicon-s-globe-alt';

    protected static ?string $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'Pengaturan Website';

    protected static ?string $modelLabel = 'Pengaturan Website';

    protected static ?string $pluralModelLabel = 'Pengaturan Website';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Website')
                    ->description('Atur identitas utama website Tukang Print Dadakan.')
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        Forms\Components\TextInput::make('nama_website')
                            ->label('Nama Website')
                            ->placeholder('Tukang Print Dadakan')
                            ->required()
                            ->maxLength(255)
                            ->default('Tukang Print Dadakan'),

                        Forms\Components\FileUpload::make('logo')
                            ->label('Logo Website')
                            ->image()
                            ->directory('website/logo')
                            ->imageEditor()
                            ->maxSize(2048),

                        Forms\Components\FileUpload::make('favicon')
                            ->label('Favicon')
                            ->image()
                            ->directory('website/favicon')
                            ->imageEditor()
                            ->maxSize(1024),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Hero Section')
                    ->description('Atur tulisan dan gambar utama pada halaman beranda.')
                    ->icon('heroicon-o-sparkles')
                    ->schema([
                        Forms\Components\TextInput::make('hero_title')
                            ->label('Judul Hero')
                            ->placeholder('Tukang Print Dadakan')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('hero_subtitle')
                            ->label('Subjudul Hero')
                            ->placeholder('Solusi cepat dan mudah untuk kebutuhan print mahasiswa.')
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('hero_image')
                            ->label('Gambar Hero')
                            ->image()
                            ->directory('website/hero')
                            ->imageEditor()
                            ->maxSize(3072)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Informasi Kontak')
                    ->description('Informasi ini akan ditampilkan pada halaman kontak dan footer website.')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        Forms\Components\TextInput::make('nomor_whatsapp')
                            ->label('Nomor WhatsApp')
                            ->placeholder('08xxxxxxxxxx')
                            ->tel()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->placeholder('tukangprint@gmail.com')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('jam_operasional')
                            ->label('Jam Operasional')
                            ->placeholder('Senin - Jumat, kecuali tanggal merah')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat / Lokasi Pengambilan')
                            ->placeholder('Kampus UEU Tangerang')
                            ->rows(3)
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
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->square()
                    ->defaultImageUrl(url('/images/placeholder.png')),

                Tables\Columns\TextColumn::make('nama_website')
                    ->label('Nama Website')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(PengaturanWebsite $record): ?string => $record->hero_title),

                Tables\Columns\TextColumn::make('nomor_whatsapp')
                    ->label('WhatsApp')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-phone'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),

                Tables\Columns\TextColumn::make('jam_operasional')
                    ->label('Jam Operasional')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\ImageColumn::make('hero_image')
                    ->label('Hero')
                    ->square()
                    ->defaultImageUrl(url('/images/placeholder.png')),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
                Tables\Actions\EditAction::make()
                    ->label('Edit'),
            ])
            ->bulkActions([])
            ->emptyStateHeading('Belum ada pengaturan website')
            ->emptyStateDescription('Tambahkan pengaturan website agar tampilan halaman publik dapat dikelola dari admin.')
            ->emptyStateIcon('heroicon-s-globe-alt');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return PengaturanWebsite::count() === 0;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengaturanWebsites::route('/'),
            'create' => Pages\CreatePengaturanWebsite::route('/create'),
            'edit' => Pages\EditPengaturanWebsite::route('/{record}/edit'),
        ];
    }
}
