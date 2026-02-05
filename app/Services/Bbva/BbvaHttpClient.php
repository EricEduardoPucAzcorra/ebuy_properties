<?php

namespace App\Services\Bbva;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class BbvaHttpClient
{
    private $apiKey;
    private $merchantId;
    private $production;
    private $client;

    private static $api_endpoint = 'https://api.ecommercebbva.com/v1/';
    private static $api_endpoint_sandbox = 'https://sand-api.ecommercebbva.com/v1/';
    private static $user_agent = 'BbvaPHP/v1';

    public function __construct(string $apiKey, string $merchantId, bool $production = false)
    {
        if (empty($apiKey)) {
            throw new \InvalidArgumentException('api_key cannot be null');
        }

        if (empty($merchantId)) {
            throw new \InvalidArgumentException('merchant_id cannot be null');
        }

        $this->apiKey = $apiKey;
        $this->merchantId = $merchantId;
        $this->production = $production;

        $baseUrl = $production ? self::$api_endpoint : self::$api_endpoint_sandbox;

        $this->client = new Client([
            'base_uri' => $baseUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'User-Agent' => self::$user_agent,
                'Authorization' => 'Basic ' . base64_encode($apiKey . ':')
            ],
            'timeout' => 120,
            'verify' => $production,
            'curl' => [
                CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
            ]
        ]);
    }

    public function post(string $endpoint, $data = null)
    {
        try {
            Log::debug('BBVA POST:', [
                'endpoint' => $endpoint,
                'merchant_id' => $this->merchantId
            ]);

            $options = [];

            if ($data !== null) {
                if (is_array($data)) {
                    $options['json'] = $data;
                } elseif (is_string($data)) {
                    $options['body'] = $data;
                }
            }

            // Agregar merchant_id al endpoint como en .NET
            $fullEndpoint = '/' . $this->merchantId . $endpoint;

            $response = $this->client->post($fullEndpoint, $options);
            $responseData = json_decode($response->getBody()->getContents(), true);

            Log::debug('BBVA Response:', $responseData);

            return $responseData;

        } catch (RequestException $e) {
            throw $this->handleException($e);
        }
    }

    public function get(string $endpoint, array $params = [])
    {
        try {
            Log::debug('BBVA GET:', [
                'endpoint' => $endpoint,
                'params' => $params
            ]);

            $options = [];
            if (!empty($params)) {
                $options['query'] = $params;
            }

            // Agregar merchant_id al endpoint
            $fullEndpoint = '/' . $this->merchantId . $endpoint;

            $response = $this->client->get($fullEndpoint, $options);
            $responseData = json_decode($response->getBody()->getContents(), true);

            Log::debug('BBVA Response:', $responseData);

            return $responseData;

        } catch (RequestException $e) {
            throw $this->handleException($e);
        }
    }

    private function handleException(RequestException $e): \Exception
    {
        if ($e->hasResponse()) {
            $statusCode = $e->getResponse()->getStatusCode();
            $body = $e->getResponse()->getBody()->getContents();

            Log::error('BBVA Error:', [
                'status' => $statusCode,
                'body' => $body
            ]);

            try {
                $errorData = json_decode($body, true);
                $message = $errorData['description'] ?? $errorData['message'] ?? $errorData['error'] ?? 'Error desconocido';

                if ($statusCode <= 500) {
                    return new \Exception("BBVA Error {$statusCode}: {$message}", $statusCode);
                }
            } catch (\Exception $parseError) {
                // Ignorar error de parseo
            }
        }

        return new \Exception('Error de conexión con BBVA: ' . $e->getMessage());
    }

    public function isProduction(): bool
    {
        return $this->production;
    }
}
