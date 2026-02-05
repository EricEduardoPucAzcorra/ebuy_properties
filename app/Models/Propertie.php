<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Propertie extends Model
{
    public $table = 'properties';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'type_property_id',
        'type_operation_id',
        'status_property_id',
        'title',
        'description',
        'price',
        'currency',
        'is_active',
    ];

    public function address()
    {
        return $this->hasOne(AddressPropertie::class, 'property_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(TypePropetie::class, 'type_property_id', 'id');
    }

}
