<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyAttribute extends Model
{
    public $table = 'property_attributes';

    public $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'property_id',
        'key',
        'value',
    ];
}
