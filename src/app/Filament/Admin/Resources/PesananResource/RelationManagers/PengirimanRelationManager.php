<?php

namespace App\Filament\Admin\Resources\PesananResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Set;

class PengirimanRelationManager extends RelationManager
{
    protected static string $relationship = 'pengiriman';

    protected static ?string $title = 'Pengiriman';

    protected static ?string $modelLabel = 'Pengiriman';

    protected static ?string $pluralModelLabel = 'Pengiriman';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengiriman')
                    ->description('Kelola metode pengambilan atau pengiriman pesanan.')
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
                            ->native(false)
                            ->live()
                            ->required()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $set('biaya_pengiriman', match ($state) {
                                    'ambil_di_kampus' => 0,
                                    'antar' => 5000,
                                    'ojek_online' => 0,
                                    default => 0,
                                });
                            }),

                        Forms\Components\TextInput::make('biaya_pengiriman')
                            ->label('Biaya Pengiriman')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('status_pengiriman')
                            ->label('Status Pengiriman')
                            ->options([
                                'belum_dikirim' => 'Belum Dikirim',
                                'diproses' => 'Diproses',
                                'dikirim' => 'Dikirim',
                                'selesai' => 'Selesai',
                            ])
                            ->default('belum_dikirim')
                            ->native(false)
                            ->required(),

                        Forms\Components\Textarea::make('alamat_pengiriman')
                            ->label('Alamat Pengiriman')
                            ->placeholder('Isi jika metode pengiriman adalah diantar atau ojek online.')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('catatan_pengiriman')
                            ->label('Catatan Pengiriman')
                            ->placeholder('Contoh: Ambil di lobby kampus, tunggu konfirmasi ojek online, dan sebagainya.')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('metode_pengiriman')
            ->columns([
                Tables\Columns\TextColumn::make('metode_pengiriman')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'ambil_di_kampus' => 'Ambil di Kampus',
                        'antar' => 'Diantar',
                        'ojek_online' => 'Ojek Online',
                        default => ucfirst($state),
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'ambil_di_kampus' => 'gray',
                        'antar' => 'info',
                        'ojek_online' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('alamat_pengiriman')
                    ->label('Alamat')
                    ->limit(45)
                    ->wrap()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('biaya_pengiriman')
                    ->label('Biaya')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('status_pengiriman')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'belum_dikirim' => 'Belum Dikirim',
                        'diproses' => 'Diproses',
                        'dikirim' => 'Dikirim',
                        'selesai' => 'Selesai',
                        default => ucfirst($state),
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'belum_dikirim' => 'gray',
                        'diproses' => 'warning',
                        'dikirim' => 'info',
                        'selesai' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('catatan_pengiriman')
                    ->label('Catatan')
                    ->limit(40)
                    ->toggleable()
                    ->placeholder('-'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Pengiriman'),
            ])
            ->actions([
                Tables\Actions\Action::make('proses')
                    ->label('Proses')
                    ->icon('heroicon-o-clock')
                    ->color('warning')
                    ->visible(fn($record): bool => $record->status_pengiriman === 'belum_dikirim')
                    ->action(fn($record) => $record->update([
                        'status_pengiriman' => 'diproses',
                    ])),

                Tables\Actions\Action::make('dikirim')
                    ->label('Dikirim')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn($record): bool => $record->status_pengiriman === 'diproses')
                    ->action(fn($record) => $record->update([
                        'status_pengiriman' => 'dikirim',
                    ])),

                Tables\Actions\Action::make('selesai')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record): bool => in_array($record->status_pengiriman, ['diproses', 'dikirim']))
                    ->action(fn($record) => $record->update([
                        'status_pengiriman' => 'selesai',
                    ])),

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
            ->emptyStateHeading('Belum ada data pengiriman')
            ->emptyStateDescription('Tambahkan data pengiriman atau pengambilan untuk pesanan ini.')
            ->emptyStateIcon('heroicon-o-truck');
    }
}
