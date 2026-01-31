<?php

namespace Database\Seeders;

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
        $csvFile = database_path('documents/countries.csv');

        $csv = Reader::createFromPath($csvFile, 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();

        foreach ($records as $record) {
            if (
                ($record['region'] ?? null) !== 'Americas' ||
                ($record['region_id'] ?? null) !== '2'
            ) {
                continue;
            }


            DB::table('countries')->updateOrInsert(
                ['countryid' => $record['id']],
                [
                    'countryName' => $this->fixEncoding($record['name']),
                    'countryCode' => $record['iso3'],
                    'continent'   => $this->fixEncoding($record['region']),
                    'latitude'    => $record['latitude'],
                    'longitude'   => $record['longitude'],
                    'name'        => $this->fixEncoding($record['name']),
                    'code'        => $record['iso2'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );
        }
    }

    private function fixEncoding(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $encoding = mb_detect_encoding(
            $value,
            ['UTF-8', 'ISO-8859-1', 'Windows-1252'],
            true
        );

        return mb_convert_encoding($value, 'UTF-8', $encoding ?: 'ISO-8859-1');
    }
}
