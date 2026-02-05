<?php

namespace App\Services\Bbva\Entities;

class SingleParameter implements IParameter
{
    public $ParameterName;
    public $ParameterValue;

    public function __construct(string $name, string $value)
    {
        $this->ParameterName = $name;
        $this->ParameterValue = $value;
    }

    public function getParameterName(): string
    {
        return $this->ParameterName;
    }

    public function getParameterValue(): string
    {
        return $this->ParameterValue;
    }
}
