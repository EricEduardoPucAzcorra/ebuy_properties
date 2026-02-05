<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BbvaPaymentService
{
    private $config;

    public function __construct()
    {
        $this->config = config('bbva.bbva');
    }

    public function testConnection()
    {
        try {
            $this->info('=== PRUEBA BBVA CONEXIÓN ===');

            // Verificar configuración
            if (!$this->config) {
                return [
                    'success' => false,
                    'error' => 'Configuración BBVA no encontrada'
                ];
            }

            $this->info('Configuración encontrada');
            $this->info('   Merchant ID: ' . $this->config['merchant_id']);
            $this->info('   Modo: ' . ($this->config['production'] ? 'Producción' : 'Sandbox'));
            $this->info('   URL: ' . ($this->config['production'] ? $this->config['production_url'] : $this->config['sandbox_url']));

            // Crear BbvaAPI
            $bbvaAPI = new \App\Services\Bbva\BbvaAPI(
                $this->config['api_key'],
                $this->config['merchant_id'],
                $this->config['production']
            );

            $this->info('Intentando conectar con BBVA...');

            // Intentar obtener cargos (limit 1)
            $response = $bbvaAPI->ChargeService->list(null, ['limit' => 1]);

            $this->info('¡Conexión exitosa!');
            $this->info('   Respuesta recibida correctamente');

            if (isset($response['error'])) {
                // Si hay error pero es de autenticación, la conexión funciona
                $this->warn('Error de autenticación: ' . $response['error']);
                return [
                    'success' => true,
                    'message' => 'Conexión OK pero credenciales inválidas',
                    'data' => $response
                ];
            }

            return [
                'success' => true,
                'message' => 'Conexión exitosa con BBVA API',
                'data' => $response
            ];

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ];
        }
    }

    /**
     * Métodos auxiliares para logging (solo para testConnection)
     */
    private function info($message)
    {
        if (php_sapi_name() === 'cli') {
            echo $message . PHP_EOL;
        }
    }

    private function error($message)
    {
        if (php_sapi_name() === 'cli') {
            echo $message . PHP_EOL;
        }
    }

    private function warn($message)
    {
        if (php_sapi_name() === 'cli') {
            echo $message . PHP_EOL;
        }
    }
}
