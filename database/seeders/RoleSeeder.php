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

        $client = Role::firstOrCreate(
            ['name' => 'Cliente'],
            ['description' => 'Usuario que puede realizar solicitudes de servicio']
        );


        $agent = Role::firstOrCreate(
            ['name' => 'Agente'],
            ['description' => 'Usuario que gestiona las solicitudes de servicio']
        );

        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            $admin->permissions()->syncWithoutDetaching($permission->id);
        }
    }
}
