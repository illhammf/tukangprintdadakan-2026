<?php

namespace App\Filament\Admin\Resources\RiwayatStatusPesananResource\Pages;

use App\Filament\Admin\Resources\RiwayatStatusPesananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRiwayatStatusPesanan extends EditRecord
{
    protected static string $resource = RiwayatStatusPesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
