<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dashboard = Module::where('name','=','dashboard')->first();
        $users = Module::where('name','=','users')->first();
        $roles = Module::where('name','=','roles')->first();
        $config = Module::where('name','=','config')->first();


        $permissions = [
            [
                'name' => 'dashboard.dashboard',
                'description' => 'Ver dashboard',
                'slug' => 'dashboard.index',
                'module_id' => $dashboard->id
            ],
            [
                'name' => 'users.view',
                'description' => '',
                'slug' => 'users.view',
                'module_id' => $users->id
            ],
            [
                'name' => 'users.create',
                'description' => '',
                'slug' => 'users.new',
                'module_id' => $users->id
            ],
            [
                'name' => 'users.update',
                'description' => '',
                'slug' => 'users.edit',
                'module_id' => $users->id
            ],
            [
                'name' => 'users.state_update',
                'description' => '',
                'slug' => 'users.state_update',
                'module_id' => $users->id
            ],
            [
                'name' => 'users.show',
                'description' => '',
                'slug' => 'users.show',
                'module_id' => $users->id
            ],
            [
                'name' => 'roles.view',
                'description' => '',
                'slug' => 'roles.view',
                'module_id' => $roles->id
            ],

            [
                'name' => 'roles.create',
                'description' => '',
                'slug' => 'roles.create',
                'module_id' => $roles->id
            ],

            [
                'name' => 'roles.update',
                'description' => '',
                'slug' => 'roles.update',
                'module_id' => $roles->id
            ],

            [
                'name' => 'roles.permissions',
                'description' => '',
                'slug' => 'roles.permissions',
                'module_id' => $roles->id
            ],


            [
                'name' => 'config.view',
                'description' => '',
                'slug' => 'config.view',
                'module_id' => $config->id
            ],

            [
                'name' => 'config.set_up_global',
                'description' => '',
                'slug' => 'config.set_up_global',
                'module_id' => $config->id
            ],

        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

    }
}
