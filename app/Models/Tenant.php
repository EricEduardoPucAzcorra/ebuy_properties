<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
     protected $fillable = [
        'name',
        'legalName',
        'taxId',
        'email',
        'phone',
        'address',
        'country_id',
        'is_principal',
        'user_id',
        'logo',
        'latitude',
        'longitude',
        'is_active',
        'tenant_created_id'
    ];

    public function country()
    {
        return $this->belongsTo(Countrie::class, 'country_id');
    }
}
