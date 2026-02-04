<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Module;
use App\Models\Permission;
use App\Models\StatePropertie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatePropertieSeeder extends Seeder
{
    public function run(): void
    {
       StatePropertie::firstOrCreate(
            ['name' => 'published'],
            ['description' => ''],
            ['is_active' => true],
            ['slug' => 'published']
        );
        StatePropertie::firstOrCreate(
            ['name' => 'unpublished'],
            ['description' => ''],
            ['is_active' => true],
            ['slug' => 'unpublished']
        );
        StatePropertie::firstOrCreate(
            ['name' => 'paused'],
            ['description' => ''],
            ['is_active' => true],
            ['slug' => 'paused']
        );
        StatePropertie::firstOrCreate(
            ['name' => 'sold'],
            ['description' => ''],
            ['is_active' => true],
            ['slug' => 'sold']
        );
        StatePropertie::firstOrCreate(
            ['name' => 'rented'],
            ['description' => ''],
            ['is_active' => true],
            ['slug' => 'rented']
        );
    }
}
