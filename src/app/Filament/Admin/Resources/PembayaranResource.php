<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PembayaranResource\Pages;
use App\Filament\Admin\Resources\PembayaranResource\RelationManagers;
use App\Models\Pembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pesanan_id')
                    ->relationship('pesanan', 'id')
                    ->required(),
                Forms\Components\TextInput::make('metode_pembayaran')
                    ->required(),
                Forms\Components\TextInput::make('channel_pembayaran')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('jumlah_bayar')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('bukti_pembayaran')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('status_pembayaran')
                    ->required(),
                Forms\Components\DateTimePicker::make('tanggal_bayar'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pesanan.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('metode_pembayaran'),
                Tables\Columns\TextColumn::make('channel_pembayaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah_bayar')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bukti_pembayaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_pembayaran'),
                Tables\Columns\TextColumn::make('tanggal_bayar')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }
}
