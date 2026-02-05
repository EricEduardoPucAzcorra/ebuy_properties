<?php

namespace App\Services\Bbva;

use App\Services\Bbva\BbvaHttpClient;

class ChargeService
{
    private $httpClient;
    private $resourceName = 'charges';

    public function __construct(BbvaHttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Crear cargo
     */
    public function create(array $charge_request)
    {
        // Convertir parámetros a array
        $data = $this->parametersToArray($charge_request);
        return $this->httpClient->post('/charges', $data);
    }

    /**
     * Obtener cargo por ID
     */
    public function get(string $charge_id)
    {
        return $this->httpClient->get('/charges/' . $charge_id);
    }

    /**
     * Listar cargos
     */
    public function list(?string $customer_id = null, array $params = [])
    {
        $endpoint = $customer_id ? '/customers/' . $customer_id . '/charges' : '/charges';
        return $this->httpClient->get($endpoint, $params);
    }

    /**
     * Capturar cargo
     */
    public function capture(?string $customer_id, string $charge_id, ?float $amount = null)
    {
        $endpoint = $this->getEndPoint($customer_id, $charge_id) . '/capture';
        $data = $amount !== null ? ['amount' => $amount] : [];
        return $this->httpClient->post($endpoint, $data);
    }

    /**
     * Reembolsar cargo
     */
    public function refund(?string $customer_id, string $charge_id, string $description, ?float $amount = null)
    {
        $endpoint = $this->getEndPoint($customer_id, $charge_id) . '/refund';
        $data = ['description' => $description];
        if ($amount !== null) {
            $data['amount'] = $amount;
        }
        return $this->httpClient->post($endpoint, $data);
    }

    /**
     * Obtener endpoint
     */
    private function getEndPoint(?string $customer_id = null, ?string $resource_id = null): string
    {
        $ep = '/charges';
        if ($customer_id !== null) {
            $ep = '/customers/' . $customer_id . $ep;
        }
        if ($resource_id !== null) {
            $ep = $ep . '/' . $resource_id;
        }
        return $ep;
    }

    /**
     * Convertir parámetros IParameter a array
     */
    private function parametersToArray(array $parameters): array
    {
        $result = [];

        foreach ($parameters as $parameter) {
            if ($parameter instanceof \App\Services\Bbva\Entities\SingleParameter) {
                $result[$parameter->ParameterName] = $parameter->ParameterValue;
            } elseif ($parameter instanceof \App\Services\Bbva\Entities\ParameterContainer) {
                $containerData = [];
                foreach ($parameter->ParameterValues as $param) {
                    if ($param instanceof \App\Services\Bbva\Entities\SingleParameter) {
                        $containerData[$param->ParameterName] = $param->ParameterValue;
                    } elseif ($param instanceof \App\Services\Bbva\Entities\ParameterContainer) {
                        // Manejar sub-contenedores
                        $subContainerData = [];
                        foreach ($param->ParameterValues as $subParam) {
                            if ($subParam instanceof \App\Services\Bbva\Entities\SingleParameter) {
                                $subContainerData[$subParam->ParameterName] = $subParam->ParameterValue;
                            }
                        }
                        $containerData[$param->ParameterName] = $subContainerData;
                    }
                }
                $result[$parameter->ParameterName] = $containerData;
            }
        }

        return $result;
    }
}
