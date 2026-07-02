<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PengirimanResource\Pages;
use App\Models\Pengiriman;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PengirimanResource extends Resource
{
    protected static ?string $model = Pengiriman::class;

    protected static ?string $navigationIcon = 'heroicon-s-truck';

    protected static ?string $navigationGroup = 'Pemesanan';

    protected static ?string $navigationLabel = 'Pengiriman';

    protected static ?string $modelLabel = 'Pengiriman';

    protected static ?string $pluralModelLabel = 'Pengiriman';

    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Relasi Pesanan')
                    ->description('Pilih pesanan yang memiliki data pengiriman atau pengambilan.')
                    ->icon('heroicon-o-shopping-bag')
                    ->schema([
                        Forms\Components\Select::make('pesanan_id')
                            ->label('Kode Pesanan')
                            ->relationship(
                                name: 'pesanan',
                                titleAttribute: 'kode_pesanan',
                                modifyQueryUsing: fn ($query) => $query->whereDoesntHave('pengiriman')
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabledOn('edit'),
                    ]),

                Forms\Components\Section::make('Informasi Pengiriman')
                    ->description('Atur metode pengambilan atau pengiriman pesanan pelanggan.')
                    ->icon('heroicon-o-truck')
                    ->schema([
                        Forms\Components\Select::make('metode_pengiriman')
                            ->label('Metode Pengiriman')
                            ->options([
                                'ambil_di_kampus' => 'Ambil di Kampus',
                                'antar' => 'Diantar',
                                'ojek_online' => 'Ojek Online',
                            ])
                            ->default('ambil_di_kampus')
                            ->required(),

                        Forms\Components\TextInput::make('biaya_pengiriman')
                            ->label('Biaya Pengiriman')
                            ->prefix('Rp') // Menambahkan prefix "Rp" untuk menandakan mata uang Rupiah
                            ->numeric() // Untuk memastikan input hanya berupa angka
                            ->minValue(0) // Untuk memastikan nilai tidak negatif
                            ->required()
                            ->default(0), // Menetapkan default value menjadi 0

                        Forms\Components\Select::make('status_pengiriman')
                            ->label('Status Pengiriman')
                            ->options([
                                'belum_dikirim' => 'Belum Dikirim',
                                'diproses' => 'Diproses',
                                'dikirim' => 'Dikirim',
                                'selesai' => 'Selesai',
                            ])
                            ->default('belum_dikirim')
                            ->required(),

                        Forms\Components\Textarea::make('alamat_pengiriman')
                            ->label('Alamat Pengiriman')
                            ->placeholder('Isi jika metode pengiriman diantar atau ojek online.')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('catatan_pengiriman')
                            ->label('Catatan Pengiriman')
                            ->placeholder('Catatan tambahan terkait pengambilan atau pengiriman.')
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

                Tables\Columns\TextColumn::make('metode_pengiriman')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'ambil_di_kampus' => 'Ambil di Kampus',
                        'antar' => 'Diantar',
                        'ojek_online' => 'Ojek Online',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'ambil_di_kampus' => 'gray',
                        'antar' => 'info',
                        'ojek_online' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('biaya_pengiriman')
                    ->label('Biaya')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status_pengiriman')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'belum_dikirim' => 'Belum Dikirim',
                        'diproses' => 'Diproses',
                        'dikirim' => 'Dikirim',
                        'selesai' => 'Selesai',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'belum_dikirim' => 'gray',
                        'diproses' => 'warning',
                        'dikirim' => 'info',
                        'selesai' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('alamat_pengiriman')
                    ->label('Alamat')
                    ->limit(40)
                    ->wrap()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('metode_pengiriman')
                    ->label('Metode Pengiriman')
                    ->options([
                        'ambil_di_kampus' => 'Ambil di Kampus',
                        'antar' => 'Diantar',
                        'ojek_online' => 'Ojek Online',
                    ]),

                Tables\Filters\SelectFilter::make('status_pengiriman')
                    ->label('Status Pengiriman')
                    ->options([
                        'belum_dikirim' => 'Belum Dikirim',
                        'diproses' => 'Diproses',
                        'dikirim' => 'Dikirim',
                        'selesai' => 'Selesai',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),

                Tables\Actions\Action::make('diproses')
                    ->label('Proses')
                    ->icon('heroicon-o-clock')
                    ->color('warning')
                    ->visible(fn (Pengiriman $record): bool => $record->status_pengiriman === 'belum_dikirim')
                    ->action(fn (Pengiriman $record) => $record->update([
                        'status_pengiriman' => 'diproses',
                    ])),

                Tables\Actions\Action::make('dikirim')
                    ->label('Dikirim')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn (Pengiriman $record): bool => $record->status_pengiriman === 'diproses')
                    ->action(fn (Pengiriman $record) => $record->update([
                        'status_pengiriman' => 'dikirim',
                    ])),

                Tables\Actions\Action::make('selesai')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Pengiriman $record): bool => in_array($record->status_pengiriman, ['diproses', 'dikirim']))
                    ->action(fn (Pengiriman $record) => $record->update([
                        'status_pengiriman' => 'selesai',
                    ])),

                Tables\Actions\EditAction::make()
                    ->label('Edit'),
            ])
            ->bulkActions([])
            ->emptyStateHeading('Belum ada data pengiriman')
            ->emptyStateDescription('Data pengiriman atau pengambilan pesanan akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-truck');
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
            'index' => Pages\ListPengirimen::route('/'),
            'edit' => Pages\EditPengiriman::route('/{record}/edit'),
        ];
    }
}