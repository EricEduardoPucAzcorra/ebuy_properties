<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $table = 'roles';
    public $primaryKey="id";
    public $timestamps = true;
    protected $fillable = [
        'name',
        'description',
    ];
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }
}
