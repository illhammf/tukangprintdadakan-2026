<?php

namespace App\Filament\Admin\Resources;

use BezhanSalleh\FilamentShield\Resources\RoleResource as BaseRoleResource;

class RoleResource extends BaseRoleResource
{
    protected static ?int $navigationSort = -2;
    
    public static function getNavigationLabel(): string
    {
        return 'Hak Akses';
    }

    public static function getModelLabel(): string
    {
        return 'Hak Akses';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Hak Akses';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Administration';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-s-shield-check';
    }

}