<?php

return [

    'required'  => 'El campo :attribute es obligatorio.',
    'email'     => 'El campo :attribute debe ser un correo electrónico válido.',
    'unique'    => 'El :attribute ya está en uso.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'min' => [
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'max' => [
        'string' => 'El campo :attribute no debe exceder :max caracteres.',
    ],
    'boolean'   => 'El campo :attribute debe ser verdadero o falso.',
    'image'     => 'El campo :attribute debe ser una imagen.',
    'mimes'     => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'exists'    => 'El :attribute seleccionado no es válido.',
    'numeric'   => 'El campo :attribute debe ser un número.',

    'attributes' => [
        'name'              => 'nombre',
        'email'             => 'correo electrónico',
        'password'          => 'contraseña',
        'last_name'         => 'apellido paterno',
        'second_last_name'  => 'apellido materno',
        'phone'             => 'teléfono',
        'is_active'         => 'estado',
        'profile'           => 'imagen de perfil',
        'legalName'         => 'Razón social',
        'taxId'             => 'RFC / ID fiscal',
        'address'           => 'Dirección',
        'country_id'        => 'país',
        'logo'              => 'logotipo',
        'location'          => 'ubicación',
        'latitude'          => 'latitud',
        'longitude'         => 'longitud',
        'is_principal'      => 'estado principal',
        'business_units'    => 'unidades de negocio',
        'tenant_created_id' => 'ID del arrendatario',
    ],

];
