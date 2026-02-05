<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BbvaPaymentService;

class TestBbvaConnection extends Command
{
    protected $signature = 'bbva:test';
    protected $description = 'Probar conexión con BBVA';

    public function handle(): int
    {
        $this->info('=== PRUEBA BBVA CONEXIÓN ===');

        try {
            $service = new BbvaPaymentService();
            $result = $service->testConnection();

            if ($result['success']) {
                $this->info('' . $result['message']);
                return self::SUCCESS;
            } else {
                $this->error('Error: ' . $result['error']);
                return self::FAILURE;
            }

        } catch (\Exception $e) {
            $this->error('Error inesperado: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
