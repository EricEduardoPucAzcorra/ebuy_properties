<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $table = 'permissions';

    public $primaryKey="id";

    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'module_id'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_permissions'
        );
    }

    public function menuItems()
    {
        return $this->belongsToMany(
            MenuItem::class,
            'menu_item_permissions'
        );
    }
}
