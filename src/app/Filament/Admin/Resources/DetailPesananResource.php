<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DetailPesananResource\Pages;
use App\Filament\Admin\Resources\DetailPesananResource\RelationManagers;
use App\Models\DetailPesanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DetailPesananResource extends Resource
{
    protected static ?string $model = DetailPesanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pesanan_id')
                    ->relationship('pesanan', 'id')
                    ->required(),
                Forms\Components\Select::make('layanan_id')
                    ->relationship('layanan', 'id')
                    ->default(null),
                Forms\Components\TextInput::make('nama_file')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('file_path')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('jenis_print'),
                Forms\Components\TextInput::make('ukuran_kertas')
                    ->required()
                    ->maxLength(255)
                    ->default('A4'),
                Forms\Components\TextInput::make('jumlah_halaman')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('jumlah_copy')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('harga_satuan')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\Toggle::make('pakai_jilid')
                    ->required(),
                Forms\Components\Toggle::make('pakai_laminating')
                    ->required(),
                Forms\Components\Textarea::make('catatan_detail')
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
                Tables\Columns\TextColumn::make('layanan.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_file')
                    ->searchable(),
                Tables\Columns\TextColumn::make('file_path')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_print'),
                Tables\Columns\TextColumn::make('ukuran_kertas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah_halaman')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_copy')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_satuan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('pakai_jilid')
                    ->boolean(),
                Tables\Columns\IconColumn::make('pakai_laminating')
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
            'index' => Pages\ListDetailPesanans::route('/'),
            'create' => Pages\CreateDetailPesanan::route('/create'),
            'edit' => Pages\EditDetailPesanan::route('/{record}/edit'),
        ];
    }
}
