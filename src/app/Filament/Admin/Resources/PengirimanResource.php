<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PengirimanResource\Pages;
use App\Filament\Admin\Resources\PengirimanResource\RelationManagers;
use App\Models\Pengiriman;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengirimanResource extends Resource
{
    protected static ?string $model = Pengiriman::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pesanan_id')
                    ->relationship('pesanan', 'id')
                    ->required(),
                Forms\Components\TextInput::make('metode_pengiriman')
                    ->required(),
                Forms\Components\Textarea::make('alamat_pengiriman')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('biaya_pengiriman')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('status_pengiriman')
                    ->required(),
                Forms\Components\Textarea::make('catatan_pengiriman')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pesanan.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('metode_pengiriman'),
                Tables\Columns\TextColumn::make('biaya_pengiriman')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_pengiriman'),
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
            'index' => Pages\ListPengirimen::route('/'),
            'create' => Pages\CreatePengiriman::route('/create'),
            'edit' => Pages\EditPengiriman::route('/{record}/edit'),
        ];
    }
}
