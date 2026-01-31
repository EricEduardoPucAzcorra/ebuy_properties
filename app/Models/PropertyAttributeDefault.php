<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyAttributeDefault extends Model
{
    protected $table = 'property_attributes_default';

    protected $fillable = [
        'key',
    ];
}
