<?php

namespace App\Filament\Admin\Resources\RiwayatStatusPesananResource\Pages;

use App\Filament\Admin\Resources\RiwayatStatusPesananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRiwayatStatusPesanans extends ListRecords
{
    protected static string $resource = RiwayatStatusPesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
