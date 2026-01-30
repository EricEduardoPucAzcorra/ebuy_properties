<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::firstOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Administrador del sistema']
        );

        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            $admin->permissions()->syncWithoutDetaching($permission->id);
        }
    }
}
