<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $guard = config('auth.defaults.guard');
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        foreach (PermissionsEnum::cases() as $permission) {
            Permission::create(['name' => $permission->value,  'guard_name' => $guard]);
        }

        // create roles and assign created permissions
        foreach (RolesEnum::cases() as $role) {
            $role = Role::create(['name' => $role->value, 'guard_name' => $guard]);

            if ($role->name === RolesEnum::ADMIN->value) {
                $role->givePermissionTo(PermissionsEnum::all());
            } else {
                $role->givePermissionTo(PermissionsEnum::USERS_VIEW);
            }
        }
    }
}
