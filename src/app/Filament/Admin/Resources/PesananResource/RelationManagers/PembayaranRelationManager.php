<?php

namespace App\Filament\Admin\Resources\PesananResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PembayaranRelationManager extends RelationManager
{
    protected static string $relationship = 'pembayaran';

    protected static ?string $title = 'Pembayaran';

    protected static ?string $modelLabel = 'Pembayaran';

    protected static ?string $pluralModelLabel = 'Pembayaran';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pembayaran')
                    ->description('Kelola pembayaran untuk pesanan ini.')
                    ->icon('heroicon-o-credit-card')
                    ->schema([

                        Forms\Components\Select::make('metode_pembayaran')
                            ->label('Metode Pembayaran')
                            ->options([
                                'cash' => 'Cash',
                                'transfer' => 'Transfer Bank',
                                'qris' => 'QRIS',
                                'dana' => 'DANA',
                                'gopay' => 'GoPay',
                                'ovo' => 'OVO',
                            ])
                            ->native(false)
                            ->required(),

                        Forms\Components\TextInput::make('channel_pembayaran')
                            ->label('Channel')
                            ->placeholder('Contoh: BRI, DANA, QRIS')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('jumlah_bayar')
                            ->label('Jumlah Bayar')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->default(fn($livewire) => $livewire->ownerRecord->total_harga),

                        Forms\Components\Select::make('status_pembayaran')
                            ->label('Status Pembayaran')
                            ->options([
                                'menunggu' => 'Menunggu Pembayaran',
                                'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                'lunas' => 'Lunas',
                                'gagal' => 'Gagal',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->native(false)
                            ->required(),

                        Forms\Components\DateTimePicker::make('tanggal_bayar')
                            ->label('Tanggal Pembayaran')
                            ->seconds(false),

                        Forms\Components\FileUpload::make('bukti_pembayaran')
                            ->label('Bukti Pembayaran')
                            ->directory('bukti-pembayaran')
                            ->image()
                            ->imageEditor()
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull()
                            ->helperText('Upload screenshot atau foto bukti pembayaran.'),

                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('metode_pembayaran')
            ->columns([

                Tables\Columns\TextColumn::make('metode_pembayaran')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'cash' => 'Cash',
                        'transfer' => 'Transfer',
                        'qris' => 'QRIS',
                        'dana' => 'DANA',
                        'gopay' => 'GoPay',
                        'ovo' => 'OVO',
                        default => ucfirst($state),
                    })
                    ->color('primary'),

                Tables\Columns\TextColumn::make('channel_pembayaran')
                    ->label('Channel')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('jumlah_bayar')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->weight('bold'),

                Tables\Columns\ImageColumn::make('bukti_pembayaran')
                    ->label('Bukti')
                    ->circular(),

                Tables\Columns\TextColumn::make('status_pembayaran')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'menunggu' => 'Menunggu',
                        'menunggu_verifikasi' => 'Verifikasi',
                        'lunas' => 'Lunas',
                        'gagal' => 'Gagal',
                        'dibatalkan' => 'Dibatalkan',
                        default => ucfirst($state),
                    })
                    ->color(fn($state) => match ($state) {
                        'menunggu' => 'warning',
                        'menunggu_verifikasi' => 'info',
                        'lunas' => 'success',
                        'gagal' => 'danger',
                        'dibatalkan' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('tanggal_bayar')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Pembayaran'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada pembayaran')
            ->emptyStateDescription('Tambahkan data pembayaran untuk pesanan ini.')
            ->emptyStateIcon('heroicon-o-credit-card');
    }
}
