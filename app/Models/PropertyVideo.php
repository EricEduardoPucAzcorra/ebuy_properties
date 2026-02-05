<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyVideo extends Model
{
    public $table = 'property_videos';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'property_id',
        'url',
    ];

    public function property()
    {
        return $this->belongsTo(Propertie::class, 'property_id');
    }
}
