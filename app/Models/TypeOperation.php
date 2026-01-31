<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeOperation extends Model
{
    public $table = 'type_operations';

    public $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_icon',
        'is_active',
    ];
}
