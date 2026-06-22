<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PengaturanBookingResource\Pages;
use App\Filament\Admin\Resources\PengaturanBookingResource\RelationManagers;
use App\Models\PengaturanBooking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengaturanBookingResource extends Resource
{
    protected static ?string $model = PengaturanBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListPengaturanBookings::route('/'),
            'create' => Pages\CreatePengaturanBooking::route('/create'),
            'edit' => Pages\EditPengaturanBooking::route('/{record}/edit'),
        ];
    }
}
