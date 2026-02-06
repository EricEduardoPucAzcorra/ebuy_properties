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
            ['name' => 'Publicado'],
            ['description' => ''],
            ['is_active' => true],
            ['slug' => 'publicado']
        );
        StatePropertie::firstOrCreate(
            ['name' => 'Pendiente'],
            ['description' => ''],
            ['is_active' => true],
            ['slug' => 'pendiente']
        );
        StatePropertie::firstOrCreate(
            ['name' => 'No publicado'],
            ['description' => ''],
            ['is_active' => true],
            ['slug' => 'nopublicado']
        );
        StatePropertie::firstOrCreate(
            ['name' => 'Cancelado'],
            ['description' => ''],
            ['is_active' => true],
            ['slug' => 'cancelado']
        );
        StatePropertie::firstOrCreate(
            ['name' => 'Pausado'],
            ['description' => ''],
            ['is_active' => true],
            ['slug' => 'pausado']
        );

        StatePropertie::firstOrCreate(
            ['name' => 'Vendido'],
            ['description' => ''],
            ['is_active' => true],
            ['slug' => 'vendido']
        );

        StatePropertie::firstOrCreate(
            ['name' => 'Rentado'],
            ['description' => ''],
            ['is_active' => true],
            ['slug' => 'rentado']
        );
    }
}
