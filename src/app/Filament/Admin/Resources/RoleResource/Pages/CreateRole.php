<?php

namespace App\Filament\Admin\Resources\RoleResource\Pages;

use App\Filament\Admin\Resources\RoleResource;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    public Collection $permissions;

    protected function mutateFormDataBeforeCreate(array $data): array
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

    protected function afterCreate(): void
    {
        $permissionModel = Utils::getPermissionModel();

        $permissionModels = $this->permissions
            ->map(function (string $permission) use ($permissionModel) {
                return $permissionModel::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => $this->data['guard_name'],
                ]);
            });

        $this->record->syncPermissions($permissionModels);
    }
}