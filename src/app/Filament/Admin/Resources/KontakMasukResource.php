<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KontakMasukResource\Pages;
use App\Models\KontakMasuk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KontakMasukResource extends Resource
{
    protected static ?string $model = KontakMasuk::class;

    protected static ?string $navigationIcon = 'heroicon-s-inbox-stack';

    protected static ?string $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'Pesan Masuk';

    protected static ?string $modelLabel = 'Pesan Masuk';

    protected static ?string $pluralModelLabel = 'Pesan Masuk';

    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        $count = KontakMasuk::where('status_pesan', 'baru')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pengirim')
                    ->description('Informasi pelanggan yang mengirim pesan melalui halaman kontak.')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required() // Untuk memastikan email valid dan tidak kosong
                            ->maxLength(255),

                        Forms\Components\TextInput::make('nomor_whatsapp')
                            ->label('Nomor WhatsApp')
                            ->tel()
                            ->required() // Untuk memastikan nomor WhatsApp valid dan tidak kosong
                            ->maxLength(255),

                        Forms\Components\TextInput::make('subjek')
                            ->label('Subjek')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Isi Pesan')
                    ->description('Detail pesan yang dikirim pelanggan.')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->schema([
                        Forms\Components\Textarea::make('pesan')
                            ->label('Pesan')
                            ->required()
                            ->rows(6)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('status_pesan')
                            ->label('Status Pesan')
                            ->options([
                                'baru' => 'Baru',
                                'dibaca' => 'Dibaca',
                                'dibalas' => 'Dibalas',
                            ])
                            ->default('baru')
                            ->required(),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (KontakMasuk $record): ?string => $record->subjek),

                Tables\Columns\TextColumn::make('nomor_whatsapp')
                    ->label('WhatsApp')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-phone'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('pesan')
                    ->label('Pesan')
                    ->limit(60)
                    ->wrap()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status_pesan')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'baru' => 'Baru',
                        'dibaca' => 'Dibaca',
                        'dibalas' => 'Dibalas',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'baru' => 'danger',
                        'dibaca' => 'warning',
                        'dibalas' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Masuk')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pesan')
                    ->label('Status Pesan')
                    ->options([
                        'baru' => 'Baru',
                        'dibaca' => 'Dibaca',
                        'dibalas' => 'Dibalas',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('hubungi_whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(function (KontakMasuk $record): string {
                        $nomor = preg_replace('/[^0-9]/', '', $record->nomor_whatsapp);

                        if (str_starts_with($nomor, '0')) {
                            $nomor = '62' . substr($nomor, 1);
                        }

                        return 'https://wa.me/' . $nomor;
                    })
                    ->openUrlInNewTab()
                    ->visible(fn (KontakMasuk $record): bool => filled($record->nomor_whatsapp)),
                    
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),

                Tables\Actions\Action::make('tandai_dibaca')
                    ->label('Tandai Dibaca')
                    ->icon('heroicon-o-eye')
                    ->color('warning')
                    ->visible(fn (KontakMasuk $record): bool => $record->status_pesan === 'baru')
                    ->action(fn (KontakMasuk $record) => $record->update([
                        'status_pesan' => 'dibaca',
                    ])),

                Tables\Actions\Action::make('tandai_dibalas')
                    ->label('Tandai Dibalas')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (KontakMasuk $record): bool => $record->status_pesan !== 'dibalas')
                    ->action(fn (KontakMasuk $record) => $record->update([
                        'status_pesan' => 'dibalas',
                    ])),

                Tables\Actions\EditAction::make()
                    ->label('Edit'),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ])
            ->emptyStateHeading('Belum ada pesan masuk')
            ->emptyStateDescription('Pesan dari halaman kontak akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-inbox-stack');
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
            'index' => Pages\ListKontakMasuks::route('/'),
            'edit' => Pages\EditKontakMasuk::route('/{record}/edit'),
        ];
    }
}