<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TranslationService;
use Illuminate\Support\Facades\File;

class TranslateLangFiles extends Command
{
    protected $signature = 'lang:translate
                            {source=es : Idioma fuente}
                            {target=en : Idioma destino}
                            {--force : Sobreescribir archivos existentes}';

    protected $description = 'Traducir archivos de idioma usando Google Translate';

    public function handle()
    {
        $source = $this->argument('source');
        $target = $this->argument('target');
        $force = $this->option('force');

        $service = app(TranslationService::class)->setSourceLang($source);

        // Para archivos JSON
        $sourcePath = lang_path("{$source}.json");
        if (File::exists($sourcePath)) {
            $this->translateJsonFile($service, $sourcePath, $target, $force);
        }

        // Para archivos PHP (por carpetas)
        $sourceDir = lang_path($source);
        if (File::isDirectory($sourceDir)) {
            $this->translatePhpFiles($service, $sourceDir, $target, $force);
        }

        $this->info('Traducción completada!');
    }

    protected function translateJsonFile($service, $sourcePath, $target, $force)
    {
        $targetPath = lang_path("{$target}.json");

        if (File::exists($targetPath) && !$force) {
            if (!$this->confirm("El archivo {$target}.json ya existe. ¿Sobreescribir?")) {
                return;
            }
        }

        $sourceTranslations = json_decode(File::get($sourcePath), true);
        $translated = [];

        $bar = $this->output->createProgressBar(count($sourceTranslations));

        foreach ($sourceTranslations as $key => $text) {
            $translated[$key] = $service->translate($text, $target);
            $bar->advance();
            usleep(100000); // 100ms delay para no saturar API
        }

        File::put($targetPath, json_encode($translated, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $bar->finish();
        $this->newLine();
    }

    protected function translatePhpFiles($service, $sourceDir, $target, $force)
    {
        $targetDir = lang_path($target);

        if (!File::isDirectory($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $files = File::allFiles($sourceDir);

        foreach ($files as $file) {
            $relativePath = $file->getRelativePathname();
            $targetPath = $targetDir . '/' . $relativePath;

            if (File::exists($targetPath) && !$force) {
                continue;
            }

            // Crear directorios necesarios
            File::ensureDirectoryExists(dirname($targetPath));

            $sourceTranslations = require $file->getPathname();
            $translated = $this->translateArray($service, $sourceTranslations, $target);

            $content = "<?php\n\nreturn " . var_export($translated, true) . ";\n";
            File::put($targetPath, $content);

            $this->info("Traducido: {$relativePath}");
        }
    }

    protected function translateArray($service, $array, $target)
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->translateArray($service, $value, $target);
            } else {
                $result[$key] = $service->translate($value, $target);
                usleep(100000); // 100ms delay
            }
        }

        return $result;
    }
}
