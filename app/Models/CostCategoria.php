<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostCategoria extends Model
{
    public $table = 'cost_categories';
    public $primaryKey = 'id';
    public $timestamps = true;

    public $fillable = [
        'key',
        'name'
    ];
}
