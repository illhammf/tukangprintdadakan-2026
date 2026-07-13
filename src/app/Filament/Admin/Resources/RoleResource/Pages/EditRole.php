<?php

namespace App\Filament\Admin\Resources\RoleResource\Pages;

use App\Filament\Admin\Resources\RoleResource;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    public Collection $permissions;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $tenantForeignKey = Utils::getTenantModelForeignKey();

        $this->permissions = collect($data)
            ->filter(function ($permission, $key) use ($tenantForeignKey) {
                return ! in_array(
                    $key,
                    [
                        'name',
                        'guard_name',
                        'select_all',
                        $tenantForeignKey,
                    ],
                    true
                );
            })
            ->values()
            ->flatten()
            ->filter()
            ->unique()
            ->values();

        if (
            $tenantForeignKey
            && Arr::has($data, $tenantForeignKey)
        ) {
            return Arr::only(
                $data,
                [
                    'name',
                    'guard_name',
                    $tenantForeignKey,
                ]
            );
        }

        return Arr::only(
            $data,
            [
                'name',
                'guard_name',
            ]
        );
    }

    protected function afterSave(): void
    {
        $permissionModel = Utils::getPermissionModel();

        $permissionModels = $this->permissions
            ->map(function ($permission) use ($permissionModel) {
                return $permissionModel::firstOrCreate([
                    'name' => (string) $permission,
                    'guard_name' => $this->data['guard_name'],
                ]);
            });

        $this->record->syncPermissions($permissionModels);
    }
}