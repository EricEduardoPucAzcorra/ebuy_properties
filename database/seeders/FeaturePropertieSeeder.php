<?php

namespace Database\Seeders;

use App\Models\PropertyFeature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FeaturePropertieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PropertyFeature::firstOrCreate(['name' => 'Cocina equipada', 'description' => '', 'icon'=>'']);
        PropertyFeature::firstOrCreate(['name' => 'Amueblada', 'description' => '', 'icon'=>'']);
        PropertyFeature::firstOrCreate(['name' => 'Clósets', 'description' => '', 'icon'=>'']);
        PropertyFeature::firstOrCreate(['name' => 'Balcón', 'description' => '', 'icon'=>'']);
        PropertyFeature::firstOrCreate(['name' => 'Terraza', 'description' => '', 'icon'=>'']);
        PropertyFeature::firstOrCreate(['name' => 'Aire acondicionado', 'description' => '', 'icon'=>'']);
        PropertyFeature::firstOrCreate(['name' => 'Calefacción', 'description' => '', 'icon'=>'']);
        PropertyFeature::firstOrCreate(['name' => 'Iluminación natural', 'description' => '', 'icon'=>'']);
        PropertyFeature::firstOrCreate(['name' => 'Vista (calle, jardín, ciudad)', 'description' => '', 'icon'=>'']);
        PropertyFeature::firstOrCreate(['name' => 'Seguridad 24/7', 'description' => '', 'icon'=>'']);
        PropertyFeature::firstOrCreate(['name' => 'Elevador', 'description' => '', 'icon'=>'']);
        PropertyFeature::firstOrCreate(['name' => 'Alberca', 'description' => '', 'icon'=>'']);
        PropertyFeature::firstOrCreate(['name' => 'Gimnasio', 'description' => '', 'icon'=>'']);
        PropertyFeature::firstOrCreate(['name' => 'Jardines', 'description' => '', 'icon'=>'']);
        PropertyFeature::firstOrCreate(['name' => 'Área infantil', 'description' => '', 'icon'=>'']);
        PropertyFeature::firstOrCreate(['name' => 'Salón de eventos', 'description' => '', 'icon'=>'']);
        PropertyFeature::firstOrCreate(['name' => 'Estacionamiento techado', 'description' => '', 'icon'=>'']);
        PropertyFeature::firstOrCreate(['name' => 'Acceso controlado', 'description' => '', 'icon'=>'']);
    }
}
