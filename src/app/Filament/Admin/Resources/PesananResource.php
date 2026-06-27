<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PesananResource\Pages;
use App\Filament\Admin\Resources\PesananResource\RelationManagers;
use App\Models\Pesanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PesananResource extends Resource
{
    protected static ?string $model = Pesanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(null),
                Forms\Components\TextInput::make('kode_pesanan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_pelanggan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('nomor_whatsapp')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\DatePicker::make('tanggal_pesan')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_pengambilan'),
                Forms\Components\TextInput::make('jam_pengambilan'),
                Forms\Components\TextInput::make('lokasi_pengambilan')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('detail_lokasi')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('catatan')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('biaya_tambahan')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('biaya_pengiriman')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('total_harga')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('status_pesanan')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kode_pesanan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_pelanggan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomor_whatsapp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_pesan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_pengambilan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_pengambilan'),
                Tables\Columns\TextColumn::make('lokasi_pengambilan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('biaya_tambahan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('biaya_pengiriman')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_harga')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_pesanan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListPesanans::route('/'),
            'create' => Pages\CreatePesanan::route('/create'),
            'edit' => Pages\EditPesanan::route('/{record}/edit'),
        ];
    }
}
