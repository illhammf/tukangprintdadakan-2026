<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PengaturanWebsiteResource\Pages;
use App\Filament\Admin\Resources\PengaturanWebsiteResource\RelationManagers;
use App\Models\PengaturanWebsite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengaturanWebsiteResource extends Resource
{
    protected static ?string $model = PengaturanWebsite::class;

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
            'index' => Pages\ListPengaturanWebsites::route('/'),
            'create' => Pages\CreatePengaturanWebsite::route('/create'),
            'edit' => Pages\EditPengaturanWebsite::route('/{record}/edit'),
        ];
    }
}
