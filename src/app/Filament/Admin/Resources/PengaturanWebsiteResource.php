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
                Forms\Components\TextInput::make('nama_website')
                    ->required()
                    ->maxLength(255)
                    ->default('Tukang Print Dadakan'),
                Forms\Components\TextInput::make('logo')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('favicon')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('hero_title')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('hero_subtitle')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('hero_image')
                    ->image(),
                Forms\Components\TextInput::make('nomor_whatsapp')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('alamat')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('jam_operasional')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_website')
                    ->searchable(),
                Tables\Columns\TextColumn::make('logo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('favicon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hero_title')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('hero_image'),
                Tables\Columns\TextColumn::make('nomor_whatsapp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jam_operasional')
                    ->searchable(),
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
            'index' => Pages\ListPengaturanWebsites::route('/'),
            'create' => Pages\CreatePengaturanWebsite::route('/create'),
            'edit' => Pages\EditPengaturanWebsite::route('/{record}/edit'),
        ];
    }
}
