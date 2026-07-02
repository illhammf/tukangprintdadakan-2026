<?php

namespace App\Filament\Admin\Resources\DetailPesananResource\Pages;

use App\Filament\Admin\Resources\DetailPesananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDetailPesanans extends ListRecords
{
    protected static string $resource = DetailPesananResource::class;

    protected function getHeaderActions(): array
    {
        return []; // Ubah menjadi array kosong untuk menghapus tombol "Create"
    }
}
