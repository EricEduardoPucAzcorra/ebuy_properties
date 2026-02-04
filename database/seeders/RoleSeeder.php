<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::firstOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Administrador del sistema']
        );

        $owner = Role::firstOrCreate(
            ['name' => 'Owner'],
            ['description' => 'Dueño de condominios']
        );

        $allPermissions = Permission::pluck('id');
        $admin->permissions()->sync($allPermissions);

        $configSitePermission = Permission::where('name', 'view.site')->first();

        if ($configSitePermission) {
            $owner->permissions()->sync([$configSitePermission->id]);
        }
    }
}
