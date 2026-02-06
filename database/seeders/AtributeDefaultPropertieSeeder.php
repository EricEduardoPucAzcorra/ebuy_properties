<?php

namespace Database\Seeders;

use App\Models\PropertyAttributeDefault;
use App\Models\PropertyFeature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AtributeDefaultPropertieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PropertyAttributeDefault::firstOrCreate(['key' => 'Camas']);
        PropertyAttributeDefault::firstOrCreate(['key' => 'Baños']);
        PropertyAttributeDefault::firstOrCreate(['key' => 'Pisos']);
        PropertyAttributeDefault::firstOrCreate(['key' => 'M²']);
    }
}
