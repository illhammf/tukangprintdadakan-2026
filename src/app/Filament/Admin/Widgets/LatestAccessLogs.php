<?php

namespace App\Filament\Admin\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

class LatestAccessLogs extends BaseWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 99;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = '📝 Aktivitas Terbaru';

    protected static ?string $description = 'Pantau aktivitas terbaru yang terjadi pada dashboard admin.';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Activity::query()
                    ->with('causer')
                    ->latest('created_at')
                    ->limit(8)
            )
            ->heading('📝 Aktivitas Terbaru')
            ->description('Perubahan data, akses admin, dan aktivitas penting terbaru.')
            ->columns([
                Tables\Columns\TextColumn::make('log_name')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state
                        ? Str::of($state)->headline()->toString()
                        : '-'
                    )
                    ->color(fn (?string $state): string => match ($state) {
                        'resource' => 'primary',
                        'access' => 'info',
                        'model' => 'warning',
                        'notification' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('event')
                    ->label('Event')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'created' => 'Dibuat',
                        'updated' => 'Diubah',
                        'deleted' => 'Dihapus',
                        'restored' => 'Dipulihkan',
                        'login' => 'Login',
                        'logout' => 'Logout',
                        default => $state ? Str::of($state)->headline()->toString() : '-',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        'restored' => 'info',
                        'login' => 'primary',
                        'logout' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->wrap()
                    ->limit(90)
                    ->searchable()
                    ->tooltip(fn (Activity $record): ?string => $record->description),

                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Data')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(function (?string $state, Activity $record): string {
                        if (! $state || ! $record->subject_id) {
                            return '-';
                        }

                        $modelName = Str::of($state)
                            ->afterLast('\\')
                            ->headline()
                            ->toString();

                        return $modelName . ' #' . $record->subject_id;
                    }),

                Tables\Columns\TextColumn::make('causer.name')
                    ->label('User')
                    ->placeholder('Sistem')
                    ->icon('heroicon-s-user'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->since()
                    ->sortable()
                    ->tooltip(fn (Activity $record): string => $record->created_at?->format('d M Y H:i') ?? '-'),
            ])
            ->actions([])
            ->paginated(false)
            ->emptyStateHeading('Belum ada aktivitas')
            ->emptyStateDescription('Aktivitas admin akan muncul di sini setelah ada perubahan data.')
            ->emptyStateIcon('heroicon-o-document-text');
    }
}