<?php

namespace App\Services\Bbva\Entities;

class ParameterContainer implements IParameter
{
    public $ParameterName;
    public $ParameterValues = [];

    public function __construct(string $parameterName)
    {
        $this->ParameterName = $parameterName;
    }

    public function addValue(string $name, string $value): void
    {
        $this->ParameterValues[] = new SingleParameter($name, $value);
    }

    public function addMultiValue(ParameterContainer $multiValue): void
    {
        $this->ParameterValues[] = $multiValue;
    }

    public function getSingleValue(string $value): ?SingleParameter
    {
        foreach ($this->ParameterValues as $param) {
            if ($param instanceof SingleParameter && $param->ParameterName === $value) {
                return $param;
            }
        }
        return null;
    }

    public function getParameterName(): string
    {
        return $this->ParameterName;
    }

    public function getContainerValue(string $value): ?ParameterContainer
    {
        foreach ($this->ParameterValues as $param) {
            if ($param instanceof ParameterContainer && $param->ParameterName === $value) {
                return $param;
            }
        }
        return null;
    }
}
