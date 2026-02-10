<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class StatesTableSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = database_path('documents/states.csv');

        $csv = Reader::createFromPath($csvFile, 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();

        $countries = DB::table('countries')
            ->where('continent', 'Americas')
            ->pluck('countryid')
            ->flip();

        $batch = [];
        $batchSize = 1000;

        DB::transaction(function () use ($records, $countries, &$batch, $batchSize) {

            foreach ($records as $record) {

                if (!isset($countries[$record['country_id']])) {
                    continue;
                }

                $populationRaw = $record['population'] ?? null;

                $population = is_numeric($populationRaw)
                    ? (int) $populationRaw
                    : null;

                $batch[] = [
                    'stateid'    => $record['id'],
                    'countryid'  => $record['country_id'],
                    'statename'  => $this->fixEncoding($record['name']),
                    'statecode'  => $record['iso2'] ?? null,
                    'latitude'   => $record['latitude'] ?? null,
                    'longitude'  => $record['longitude'] ?? null,
                    'population' => $population,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($batch) >= $batchSize) {
                    DB::table('states')->upsert(
                        $batch,
                        ['stateid'],
                        ['statename', 'statecode', 'latitude', 'longitude', 'updated_at']
                    );
                    $batch = [];
                }
            }

            if (!empty($batch)) {
                DB::table('states')->upsert(
                    $batch,
                    ['stateid'],
                    ['statename', 'statecode', 'latitude', 'longitude', 'updated_at']
                );
            }
        });
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
