<?php

namespace App\Filament\Admin\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

class LatestAccessLogs extends BaseWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 99;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Aktivitas Terbaru';

    protected static ?string $description = 'Riwayat aktivitas terbaru yang terjadi pada dashboard admin.';

    protected static function getLogNameColors(): array
    {
        $customs = [];

        foreach (config('filament-logger.custom') ?? [] as $custom) {
            if (filled($custom['color'] ?? null)) {
                $customs[$custom['color']] = $custom['log_name'];
            }
        }

        return array_merge(
            (config('filament-logger.resources.enabled') && config('filament-logger.resources.color')) ? [
                config('filament-logger.resources.color') => config('filament-logger.resources.log_name'),
            ] : [],
            (config('filament-logger.models.enabled') && config('filament-logger.models.color')) ? [
                config('filament-logger.models.color') => config('filament-logger.models.log_name'),
            ] : [],
            (config('filament-logger.access.enabled') && config('filament-logger.access.color')) ? [
                config('filament-logger.access.color') => config('filament-logger.access.log_name'),
            ] : [],
            (config('filament-logger.notifications.enabled') && config('filament-logger.notifications.color')) ? [
                config('filament-logger.notifications.color') => config('filament-logger.notifications.log_name'),
            ] : [],
            $customs,
        );
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Activity::query()
                    ->latest()
                    ->limit(5)
            )
            ->heading('Aktivitas Terbaru')
            ->description('Pantau perubahan data, login, dan aktivitas penting terbaru.')
            ->columns([
                Tables\Columns\TextColumn::make('log_name')
                    ->label('Tipe')
                    ->badge()
                    ->colors(static::getLogNameColors())
                    ->formatStateUsing(fn (?string $state): string => $state ? Str::of($state)->headline()->toString() : '-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('event')
                    ->label('Event')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'created' => 'Dibuat',
                        'updated' => 'Diubah',
                        'deleted' => 'Dihapus',
                        'login' => 'Login',
                        'logout' => 'Logout',
                        default => $state ? Str::of($state)->headline()->toString() : '-',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        'login' => 'info',
                        'logout' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->wrap()
                    ->limit(80)
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Data')
                    ->formatStateUsing(function ($state, Model $record): string {
                        /** @var Activity $record */
                        if (! $state) {
                            return '-';
                        }

                        $modelName = Str::of($state)->afterLast('\\')->headline();

                        return $modelName . ' #' . $record->subject_id;
                    }),

                Tables\Columns\TextColumn::make('causer.name')
                    ->label('User')
                    ->placeholder('Sistem')
                    ->icon('heroicon-o-user'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->since(),
            ])
            ->paginated(false);
    }
}