<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypePropetie extends Model
{
    public $table = 'type_properties';

    public $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_icon',
        'is_active',
    ];

    public function properties()
    {
        return $this->hasMany(Propertie::class, 'type_property_id', 'id');
    }
}
