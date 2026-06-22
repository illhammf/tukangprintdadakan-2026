<?php

namespace App\Filament\Admin\Resources\PengaturanBookingResource\Pages;

use App\Filament\Admin\Resources\PengaturanBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengaturanBookings extends ListRecords
{
    protected static string $resource = PengaturanBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
