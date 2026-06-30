<?php

namespace App\Filament\Admin\Resources;

use Z3d0X\FilamentLogger\Resources\ActivityResource as BaseActivityResource;

class ActivityResource extends BaseActivityResource
{
    protected static ?int $navigationSort = -1;

    public static function getNavigationLabel(): string
    {
        return 'Log Aktivitas';
    }

    public static function getModelLabel(): string
    {
        return 'Log Aktivitas';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Log Aktivitas';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Administration';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-s-clock';
    }
}