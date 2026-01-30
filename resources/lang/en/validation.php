<?php

return [

    'required'  => 'The :attribute field is required.',
    'email'     => 'The :attribute must be a valid email address.',
    'unique'    => 'The :attribute has already been taken.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'min' => [
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'max' => [
        'string' => 'The :attribute may not be greater than :max characters.',
    ],
    'boolean'   => 'The :attribute field must be true or false.',
    'image'     => 'The :attribute must be an image.',
    'mimes'     => 'The :attribute must be a file of type: :values.',
    'exists'    => 'The selected :attribute is invalid.',
    'numeric'   => 'The :attribute must be a number.',

    'attributes' => [
        'name'              => 'name',
        'email'             => 'email address',
        'password'          => 'password',
        'last_name'         => 'last name',
        'second_last_name'  => 'second last name',
        'phone'             => 'phone number',
        'is_active'         => 'status',
        'profile'           => 'profile image',
        'legalName'         => 'Legal Name',
        'taxId'             => 'Tax ID',
        'address'           => 'Address',
        'country_id'        => 'Country',
        'logo'              => 'Logo',
        'location'          => 'Location',
        'latitude'          => 'Latitude',
        'longitude'         => 'Longitude',
        'is_principal'      => 'Principal status',
        'business_units'    => 'Business Units',
        'tenant_created_id' => 'Tenant ID',
    ],

];
