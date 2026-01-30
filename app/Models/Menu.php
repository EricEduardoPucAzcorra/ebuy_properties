<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public $table = 'menus';

    public $primaryKey="id";

    public $timestamps = true;

    protected $fillable = [
        'title',
        'icon',
        'route',
        'order',
        'is_active',
        'module_id'
    ];


    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu_id')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
