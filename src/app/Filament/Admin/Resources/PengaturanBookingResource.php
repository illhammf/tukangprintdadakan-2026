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
                Forms\Components\TextInput::make('nama_pengaturan')
                    ->required()
                    ->maxLength(255)
                    ->default('Default Booking'),
                Forms\Components\Toggle::make('wajib_h_minus_satu')
                    ->required(),
                Forms\Components\TextInput::make('batas_jam_booking')
                    ->required(),
                Forms\Components\Toggle::make('tutup_sabtu')
                    ->required(),
                Forms\Components\Toggle::make('tutup_minggu')
                    ->required(),
                Forms\Components\Toggle::make('tutup_tanggal_merah')
                    ->required(),
                Forms\Components\TextInput::make('maksimal_lembar_per_hari')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('maksimal_lembar_per_order')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('maksimal_jadwal_belajar_per_jam')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('minimal_hari_rapihin_tugas')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('biaya_jilid')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('biaya_laminating')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('biaya_prioritas')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\Toggle::make('aktifkan_order_prioritas')
                    ->required(),
                Forms\Components\Toggle::make('wajib_upload_bukti_online')
                    ->required(),
                Forms\Components\TextInput::make('ongkir_kampus')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\Toggle::make('lokasi_luar_kampus_perlu_konfirmasi')
                    ->required(),
                Forms\Components\Toggle::make('ojek_online_perlu_konfirmasi')
                    ->required(),
                Forms\Components\Textarea::make('catatan_booking')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pengaturan')
                    ->searchable(),
                Tables\Columns\IconColumn::make('wajib_h_minus_satu')
                    ->boolean(),
                Tables\Columns\TextColumn::make('batas_jam_booking'),
                Tables\Columns\IconColumn::make('tutup_sabtu')
                    ->boolean(),
                Tables\Columns\IconColumn::make('tutup_minggu')
                    ->boolean(),
                Tables\Columns\IconColumn::make('tutup_tanggal_merah')
                    ->boolean(),
                Tables\Columns\TextColumn::make('maksimal_lembar_per_hari')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('maksimal_lembar_per_order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('maksimal_jadwal_belajar_per_jam')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('minimal_hari_rapihin_tugas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('biaya_jilid')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('biaya_laminating')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('biaya_prioritas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('aktifkan_order_prioritas')
                    ->boolean(),
                Tables\Columns\IconColumn::make('wajib_upload_bukti_online')
                    ->boolean(),
                Tables\Columns\TextColumn::make('ongkir_kampus')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('lokasi_luar_kampus_perlu_konfirmasi')
                    ->boolean(),
                Tables\Columns\IconColumn::make('ojek_online_perlu_konfirmasi')
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
            'index' => Pages\ListPengaturanBookings::route('/'),
            'create' => Pages\CreatePengaturanBooking::route('/create'),
            'edit' => Pages\EditPengaturanBooking::route('/{record}/edit'),
        ];
    }
}
