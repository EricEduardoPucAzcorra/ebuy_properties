<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestCommand extends Command
{
    protected $signature = 'test:hello';
    protected $description = 'Comando de prueba para verificar autodiscovery';

    public function handle(): int
    {
        $this->info('¡Hola! El autodiscovery de comandos está funcionando.');
        return self::SUCCESS;
    }
}
