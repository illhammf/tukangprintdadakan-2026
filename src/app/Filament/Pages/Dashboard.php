<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-s-home';

    protected static ?string $navigationLabel = 'Beranda';

    protected static ?string $title = 'Dashboard Admin';

    protected static ?string $slug = '/';
}