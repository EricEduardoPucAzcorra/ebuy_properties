<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyImage extends Model
{
   public $table = 'property_images';
   public $primaryKey = 'id';
   public $timestamps = true;

   protected $fillable = [
        'property_id',
        'path',
        'order',
        'is_main',
   ];

   public function property()
   {
       return $this->belongsTo(Propertie::class, 'property_id');
   }
}
