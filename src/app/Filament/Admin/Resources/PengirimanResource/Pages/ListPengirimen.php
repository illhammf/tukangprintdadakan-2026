<?php

namespace App\Filament\Admin\Resources\PengirimanResource\Pages;

use App\Filament\Admin\Resources\PengirimanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengirimen extends ListRecords
{
    protected static string $resource = PengirimanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
