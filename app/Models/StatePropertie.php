<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatePropertie extends Model
{
    public $table = 'status_properties';

    public $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];
}
