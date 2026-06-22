<?php

namespace App\Filament\Admin\Resources\PengaturanBookingResource\Pages;

use App\Filament\Admin\Resources\PengaturanBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengaturanBooking extends EditRecord
{
    protected static string $resource = PengaturanBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
