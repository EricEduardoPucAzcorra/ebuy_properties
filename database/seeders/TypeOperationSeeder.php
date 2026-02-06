<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\TypeOperation;
use App\Models\TypePropetie;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TypeOperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypeOperation::firstOrCreate(['name' => 'Renta', 'slug' => 'type_operation.rentar', 'description' => 'Rentas', 'image_icon'=>'', 'is_active' => true ]);
        TypeOperation::firstOrCreate(['name' => 'Venta', 'slug' => 'type_propertie.comprar', 'description' => 'Compras', 'image_icon'=>'', 'is_active' => true ]);
        TypeOperation::firstOrCreate(['name' => 'Desarrollo', 'slug' => 'type_propertie.desarrollos', 'description' => 'Desarrollos', 'image_icon'=>'', 'is_active' => true ]);
        TypeOperation::firstOrCreate(['name' => 'Remate', 'slug' => 'type_propertie.remate', 'description' => 'Remates', 'image_icon'=>'', 'is_active' => true ]);
    }
}
