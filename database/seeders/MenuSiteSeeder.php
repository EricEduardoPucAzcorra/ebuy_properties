<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Modules para el sitio
        $site = Module::create(['name'=>"site", 'description'=>'Sitio web buy properties', 'icon'=>'bi bi-globe','order'=>0, 'clasification'=>"site"]);

        //Configurar el menu con los modulos
        Menu::firstOrCreate(['title' => 'home', 'icon' => '', 'route' => 'welcome.site', 'order' => 0, 'is_active' => true, 'module_id'=>$site->id, 'clasification'=>"site"]);
        $aboutus = Menu::firstOrCreate(['title' => 'about_us', 'icon' => '', 'route' => '', 'order' => 0, 'is_active' => true, 'module_id'=>$site->id, 'clasification'=>"site"]);
        MenuItem::create(['menu_id' => $aboutus->id, 'title' => 'about', 'icon' => 'about.site', 'route' => 'properties', 'order' => 1, 'module_id'=>$site->id]);
        $explore = Menu::firstOrCreate(['title' => 'explore', 'icon' => '', 'route' => '', 'order' => 0, 'is_active' => true, 'module_id'=>$site->id, 'clasification'=>"site"]);
        MenuItem::create(['menu_id' => $explore->id, 'title' => 'properties', 'icon' => '', 'route' => 'properties', 'order' => 1, 'module_id'=>$site->id]);

        //Menu para el owner

        $owner = Module::create(['name'=>"owner", 'description'=>'Modulos del owner', 'icon'=>'','order'=>0, 'clasification'=>"owner"]);
        Menu::firstOrCreate(['title' => 'myproperties', 'icon' => 'bi bi-houses', 'route' => 'mypropiertes', 'order' => 1, 'is_active' => true, 'module_id'=>$owner->id, 'clasification'=>"owner"]);
        Menu::firstOrCreate(['title' => 'help', 'icon' => 'bi bi-info-square', 'route' => 'help', 'order' => 2, 'is_active' => true, 'module_id'=>$owner->id, 'clasification'=>"owner"]);

    }
}
