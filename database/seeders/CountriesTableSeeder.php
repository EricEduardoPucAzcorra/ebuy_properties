<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $csvFile = database_path('documents/countrys.csv');

        $csv = Reader::createFromPath($csvFile, 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();

        foreach ($records as $record) {
            DB::table('countries')->updateOrInsert(
                ['code' => $record['code']],
                ['name' => $record['country'], 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
