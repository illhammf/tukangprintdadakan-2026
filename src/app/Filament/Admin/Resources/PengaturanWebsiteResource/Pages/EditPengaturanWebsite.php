<?php

namespace App\Filament\Admin\Resources\PengaturanWebsiteResource\Pages;

use App\Filament\Admin\Resources\PengaturanWebsiteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengaturanWebsite extends EditRecord
{
    protected static string $resource = PengaturanWebsiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
