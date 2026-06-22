<?php

namespace App\Filament\Admin\Resources\KontakMasukResource\Pages;

use App\Filament\Admin\Resources\KontakMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKontakMasuks extends ListRecords
{
    protected static string $resource = KontakMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
