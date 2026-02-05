<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\TypePropetie;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TypePropertieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypePropetie::firstOrCreate(['name' => 'Casa', 'slug' => 'type_propertie.casa', 'description' => 'Casa', 'image_icon'=>'bi bi-house', 'is_active' => true ]);
        TypePropetie::firstOrCreate(['name' => 'Departamento', 'slug' => 'type_propertie.departamento', 'description' => 'Departamento', 'image_icon'=>'bi bi-building', 'is_active' => true ]);
        TypePropetie::firstOrCreate(['name' => 'Oficina', 'slug' => 'type_propertie.oficina', 'description' => 'Oficina', 'image_icon'=>'bi bi-briefcase', 'is_active' => true ]);
        TypePropetie::firstOrCreate(['name' => 'Local Comercio', 'slug' => 'type_propertie.local', 'description' => 'Local comercio', 'image_icon'=>'bi bi-shop', 'is_active' => true ]);
        TypePropetie::firstOrCreate(['name' => 'Bodega comercial', 'slug' => 'type_propertie.bodega_comercial', 'description' => 'Bodega comercial', 'image_icon'=>'bi bi-shop-window', 'is_active' => true ]);
        TypePropetie::firstOrCreate(['name' => 'Terreno/Lote', 'slug' => 'type_propertie.lote', 'description' => 'lote', 'image_icon'=>'bi bi-tree', 'is_active' => true ]);
        TypePropetie::firstOrCreate(['name' => 'Nave industrial', 'slug' => 'type_propertie.nave', 'description' => 'nave', 'image_icon'=>'bi bi-truck-flatbed', 'is_active' => true ]);
        TypePropetie::firstOrCreate(['name' => 'Todos', 'slug' => 'type_propertie.todos', 'description' => 'todos', 'image_icon'=>'bi bi-star', 'is_active' => true ]);
    }
}
