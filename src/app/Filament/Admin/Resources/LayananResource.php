<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LayananResource\Pages;
use App\Filament\Admin\Resources\LayananResource\RelationManagers;
use App\Models\Layanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LayananResource extends Resource
{
    protected static ?string $model = Layanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kategori_layanan_id')
                    ->relationship('kategoriLayanan', 'id')
                    ->required(),
                Forms\Components\TextInput::make('nama_layanan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('deskripsi')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('harga_dasar')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('satuan')
                    ->required()
                    ->maxLength(255)
                    ->default('layanan'),
                Forms\Components\TextInput::make('gambar')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Toggle::make('butuh_upload_file')
                    ->required(),
                Forms\Components\Toggle::make('bisa_online')
                    ->required(),
                Forms\Components\Toggle::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kategoriLayanan.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_layanan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('harga_dasar')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('satuan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gambar')
                    ->searchable(),
                Tables\Columns\IconColumn::make('butuh_upload_file')
                    ->boolean(),
                Tables\Columns\IconColumn::make('bisa_online')
                    ->boolean(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
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
            'index' => Pages\ListLayanans::route('/'),
            'create' => Pages\CreateLayanan::route('/create'),
            'edit' => Pages\EditLayanan::route('/{record}/edit'),
        ];
    }
}
