<?php

namespace Database\Seeders;

use App\Models\FeatureCategory;
use App\Models\PropertyFeature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeatureCategoryPropertieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FeatureCategory::firstOrCreate(['name' => 'Características', 'description' => 'Características del lugar', 'icon'=>'bi bi-gear']);
        FeatureCategory::firstOrCreate(['name' => 'Servicios', 'description' => 'Servicios del lugar', 'icon'=>'bi bi-tools']);
        FeatureCategory::firstOrCreate(['name' => 'Amenidades', 'description' => 'Amenidades del lugar', 'icon'=>'bi bi-house-heart']);
        FeatureCategory::firstOrCreate(['name' => 'Exteriores', 'description' => 'Exteriores del lugar', 'icon'=>'bi bi-tree']);
    }
}
