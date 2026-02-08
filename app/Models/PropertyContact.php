<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyContact extends Model
{
    public $table = 'property_contact';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'property_id',
        'name',
        'phone',
        'whatsapp',
        'email',
        'date_atention',
        'photo'
    ];

    public function property()
    {
        return $this->belongsTo(Propertie::class, 'property_id');
    }
}
