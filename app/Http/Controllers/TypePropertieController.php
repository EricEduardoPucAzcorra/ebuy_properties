<?php

namespace App\Http\Controllers;

use App\Models\TypePropetie;
use Illuminate\Http\Request;

class TypePropertieController extends Controller
{
    // public function types_properties(){
    //     $types_properties = TypePropetie::all();
    //     return response()->json($types_properties);
    // }

    public function types_properties()
    {
        $types_properties = TypePropetie::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'slug'=> $item->slug,
                'name' => auto_trans($item->name),
                'image_icon'=>$item->image_icon,
                'is_active'=>$item->is_active
            ];
        });

        return response()->json($types_properties);
    }

}
