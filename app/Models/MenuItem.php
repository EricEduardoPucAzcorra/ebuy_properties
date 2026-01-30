<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    public $table = 'menu_items';

    public $primaryKey="id";

    public $timestamps = true;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'icon',
        'route',
        'order',
        'is_active',
        'module_id'
    ];

     public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('order');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'menu_item_permissions');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
