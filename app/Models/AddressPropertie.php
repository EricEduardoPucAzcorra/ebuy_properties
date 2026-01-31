<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddressPropertie extends Model
{
    public $table = 'address_properties';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'property_id',
        'street',
        'number',
        'neighborhood',
        'address',
        'country_id',
        'state_id',
        'city_id',
        'postal_code',
        'latitude',
        'longitude',
        'references',
    ];

    public function property()
    {
        return $this->belongsTo(Propertie::class, 'property_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Countrie::class, 'country_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(Citie::class, 'city_id', 'id');
    }
}
