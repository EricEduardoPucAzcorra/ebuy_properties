<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class CitiesTableSeeder extends Seeder
{
   public function run(): void
    {
        $csvFile = database_path('documents/cities.csv');

        $csv = Reader::createFromPath($csvFile, 'r');
        $csv->setHeaderOffset(0);

        $records = $csv->getRecords();

        // 🔥 Precargar datos
        $countries = DB::table('countries')
            ->where('continent', 'Americas')
            ->pluck('countryid')
            ->flip();

        $states = DB::table('states')
            ->select('stateid', 'countryid')
            ->get()
            ->map(fn ($s) => $s->countryid.'_'.$s->stateid)
            ->flip();

        $batch = [];
        $batchSize = 1000;

        DB::transaction(function () use ($records, $countries, $states, &$batch, $batchSize) {

            foreach ($records as $record) {

                if (!isset($countries[$record['country_id']])) {
                    continue;
                }

                $stateKey = $record['country_id'].'_'.$record['state_id'];
                if (!isset($states[$stateKey])) {
                    continue;
                }

                $batch[] = [
                    'cityid'     => $record['id'],
                    'stateid'    => $record['state_id'],
                    'countryid'  => $record['country_id'],
                    'cityname'   => $this->fixEncoding($record['name']),
                    'type'       => $this->fixEncoding($record['type']),
                    'latitude'   => $record['latitude'] ?? null,
                    'longitude'  => $record['longitude'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($batch) >= $batchSize) {
                    DB::table('cities')->upsert(
                        $batch,
                        ['cityid'],
                        ['cityname', 'type', 'latitude', 'longitude', 'updated_at']
                    );
                    $batch = [];
                }
            }

            // Último lote
            if (!empty($batch)) {
                DB::table('cities')->upsert(
                    $batch,
                    ['cityid'],
                    ['cityname', 'type', 'latitude', 'longitude', 'updated_at']
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
