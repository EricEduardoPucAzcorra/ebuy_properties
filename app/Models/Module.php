<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'modules';

    public $primaryKey="id";

    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'order',
        'is_active',
    ];


    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
