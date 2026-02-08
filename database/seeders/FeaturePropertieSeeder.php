<?php

namespace Database\Seeders;

use App\Models\FeatureCategory;
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
        $caracteristica = FeatureCategory::where('name', 'Características')->first();

        PropertyFeature::firstOrCreate(['name'=>'Cocina equipada','description'=>null,'icon'=>'bi bi-cup-hot','feature_category_id'=>$caracteristica->id]);
        PropertyFeature::firstOrCreate(['name'=>'Amueblada','description'=>null,'icon'=>'bi bi-house-heart','feature_category_id'=>$caracteristica->id]);
        PropertyFeature::firstOrCreate(['name'=>'Clósets','description'=>null,'icon'=>'bi bi-door-closed','feature_category_id'=>$caracteristica->id]);
        PropertyFeature::firstOrCreate(['name'=>'Aire acondicionado','description'=>null,'icon'=>'bi bi-snow','feature_category_id'=>$caracteristica->id]);
        PropertyFeature::firstOrCreate(['name'=>'Calefacción','description'=>null,'icon'=>'bi bi-thermometer-half','feature_category_id'=>$caracteristica->id]);
        PropertyFeature::firstOrCreate(['name'=>'Iluminación natural','description'=>null,'icon'=>'bi bi-brightness-high','feature_category_id'=>$caracteristica->id]);
        PropertyFeature::firstOrCreate(['name'=>'Vista panorámica','description'=>null,'icon'=>'bi bi-eye','feature_category_id'=>$caracteristica->id]);
        PropertyFeature::firstOrCreate(['name'=>'Pisos de calidad','description'=>null,'icon'=>'bi bi-layers','feature_category_id'=>$caracteristica->id]);
        PropertyFeature::firstOrCreate(['name'=>'Baño completo','description'=>null,'icon'=>'bi bi-droplet','feature_category_id'=>$caracteristica->id]);

        $service = FeatureCategory::where('name', 'Servicios')->first();

        PropertyFeature::firstOrCreate(['name'=>'Agua potable','description'=>null,'icon'=>'bi bi-droplet-fill','feature_category_id'=>$service->id]);
        PropertyFeature::firstOrCreate(['name'=>'Electricidad','description'=>null,'icon'=>'bi bi-lightning-charge','feature_category_id'=>$service->id]);
        PropertyFeature::firstOrCreate(['name'=>'Gas','description'=>null,'icon'=>'bi bi-fire','feature_category_id'=>$service->id]);
        PropertyFeature::firstOrCreate(['name'=>'Internet','description'=>null,'icon'=>'bi bi-wifi','feature_category_id'=>$service->id]);
        PropertyFeature::firstOrCreate(['name'=>'Drenaje','description'=>null,'icon'=>'bi bi-arrow-down-square','feature_category_id'=>$service->id]);
        PropertyFeature::firstOrCreate(['name'=>'Recolección de basura','description'=>null,'icon'=>'bi bi-trash','feature_category_id'=>$service->id]);
        PropertyFeature::firstOrCreate(['name'=>'Mantenimiento','description'=>null,'icon'=>'bi bi-tools','feature_category_id'=>$service->id]);
        PropertyFeature::firstOrCreate(['name'=>'Seguridad 24/7','description'=>null,'icon'=>'bi bi-shield-lock','feature_category_id'=>$service->id]);

        $amenidad = FeatureCategory::where('name', 'Amenidades')->first();

        PropertyFeature::firstOrCreate(['name'=>'Alberca','description'=>null,'icon'=>'bi bi-water','feature_category_id'=>$amenidad->id]);
        PropertyFeature::firstOrCreate(['name'=>'Gimnasio','description'=>null,'icon'=>'bi bi-activity','feature_category_id'=>$amenidad->id]);
        PropertyFeature::firstOrCreate(['name'=>'Salón de eventos','description'=>null,'icon'=>'bi bi-calendar-event','feature_category_id'=>$amenidad->id]);
        PropertyFeature::firstOrCreate(['name'=>'Área infantil','description'=>null,'icon'=>'bi bi-emoji-smile','feature_category_id'=>$amenidad->id]);
        PropertyFeature::firstOrCreate(['name'=>'Área de asadores','description'=>null,'icon'=>'bi bi-fire','feature_category_id'=>$amenidad->id]);
        PropertyFeature::firstOrCreate(['name'=>'Roof garden','description'=>null,'icon'=>'bi bi-building','feature_category_id'=>$amenidad->id]);
        PropertyFeature::firstOrCreate(['name'=>'Coworking','description'=>null,'icon'=>'bi bi-laptop','feature_category_id'=>$amenidad->id]);

        $exterior = FeatureCategory::where('name', 'Exteriores')->first();

        PropertyFeature::firstOrCreate(['name'=>'Balcón','description'=>null,'icon'=>'bi bi-layout-sidebar-inset','feature_category_id'=>$exterior->id]);
        PropertyFeature::firstOrCreate(['name'=>'Terraza','description'=>null,'icon'=>'bi bi-sun','feature_category_id'=>$exterior->id]);
        PropertyFeature::firstOrCreate(['name'=>'Jardín','description'=>null,'icon'=>'bi bi-flower1','feature_category_id'=>$exterior->id]);
        PropertyFeature::firstOrCreate(['name'=>'Patio','description'=>null,'icon'=>'bi bi-bounding-box','feature_category_id'=>$exterior->id]);
        PropertyFeature::firstOrCreate(['name'=>'Estacionamiento techado','description'=>null,'icon'=>'bi bi-car-front','feature_category_id'=>$exterior->id]);
        PropertyFeature::firstOrCreate(['name'=>'Acceso controlado','description'=>null,'icon'=>'bi bi-lock','feature_category_id'=>$exterior->id]);
        PropertyFeature::firstOrCreate(['name'=>'Portón eléctrico','description'=>null,'icon'=>'bi bi-toggle-on','feature_category_id'=>$exterior->id]);
    }
}
