<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Modules
        $dashboard = Module::create(['name'=>"dashboard", 'description'=>'', 'icon'=>'bi bi-speedometer2','order'=>1]);
        $user = Module::create(['name'=>"users", 'description'=>'', 'icon'=>'bi bi-people me-2','order'=>2]);
        $role = Module::create(['name'=>"roles", 'description'=>'', 'icon'=>'bi bi-shield-lock me-2','order'=>3]);
        $config = Module::create(['name'=>"config", 'description'=>'', 'icon'=>'bi bi-gear-fill','order'=>4]);

        //Configurar el menu con los modulos
        Menu::firstOrCreate(['title' => 'dashboard', 'icon' => 'bi bi-speedometer2', 'route' => 'home', 'order' => 1, 'is_active' => true, 'module_id'=>$dashboard->id]);
        $sistema = Menu::firstOrCreate(['title' => 'system', 'icon' => 'bi bi-gear', 'route' => '', 'order' => 2, 'is_active' => true]);

        MenuItem::create(['menu_id' => $sistema->id, 'title' => 'users', 'icon' => 'bi bi-people', 'route' => 'users', 'order' => 1, 'module_id'=>$user->id]);
        MenuItem::create(['menu_id' => $sistema->id, 'title' => 'roles', 'icon' => 'bi bi-person-rolodex', 'route' => 'roles', 'order' => 2, 'module_id'=>$role->id]);
        MenuItem::create(['menu_id' => $sistema->id, 'title' => 'config', 'icon' => 'bi bi-gear-fill', 'route' => 'config', 'order' => 2, 'module_id'=>$config->id]);

        }
}
