<?php

namespace App\Filament\Admin\Resources\PesananResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RiwayatStatusPesanansRelationManager extends RelationManager
{
    protected static string $relationship = 'riwayatStatusPesanans';

    protected static ?string $title = 'Riwayat Status';

    protected static ?string $modelLabel = 'Riwayat Status';

    protected static ?string $pluralModelLabel = 'Riwayat Status';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Catatan Status Pesanan')
                    ->description('Riwayat perubahan status pesanan dari awal sampai selesai.')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                'diproses' => 'Diproses',
                                'siap_diambil' => 'Siap Diambil',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->native(false)
                            ->required(),

                        Forms\Components\DateTimePicker::make('waktu_status')
                            ->label('Waktu Status')
                            ->seconds(false)
                            ->default(now())
                            ->required(),

                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan')
                            ->placeholder('Contoh: Pesanan sudah diverifikasi oleh admin.')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('status')
            ->defaultSort('waktu_status', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('status')
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

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(70)
                    ->wrap()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('waktu_status')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->since(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Riwayat'),
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
            ->emptyStateHeading('Belum ada riwayat status')
            ->emptyStateDescription('Perubahan status pesanan akan tercatat di sini.')
            ->emptyStateIcon('heroicon-o-clock');
    }
}