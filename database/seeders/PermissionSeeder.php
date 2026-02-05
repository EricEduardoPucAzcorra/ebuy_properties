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
        $plans = Module::where('name','=','plans')->first();
        $site = Module::where('name','=','site')->first();

        $permissions = [
            // Dashboard
            [
                'name' => 'dashboard.dashboard',
                'description' => 'Ver dashboard',
                'slug' => 'dashboard.index',
                'module_id' => $dashboard->id
            ],
            // Users
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
            // Roles
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
            // Config
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
            //Plans
            [
                'name' => 'plans.view',
                'description' => '',
                'slug' => 'plans.view',
                'module_id' => $plans->id
            ],
            [
                'name' => 'plans.create',
                'description' => '',
                'slug' => 'plans.create',
                'module_id' => $plans->id
            ],
            [
                'name' => 'plans.update',
                'description' => '',
                'slug' => 'plans.update',
                'module_id' => $plans->id
            ],

            [
                'name' => 'plan_features.view',
                'description' => '',
                'slug' => 'plan_features.plan_features',
                'module_id' => $plans->id
            ],

            // Site
            [
                'name' => 'view.site',
                'description' => '',
                'slug' => 'view.site',
                'module_id' => $site->id
            ],

            [
                'name' => 'owner.view',
                'description' => '',
                'slug' => 'view.site',
                'module_id' => $site->id
            ]

        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

    }
}
