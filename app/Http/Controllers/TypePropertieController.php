<?php

namespace App\Http\Controllers;

use App\Models\TypePropetie;
use Illuminate\Http\Request;

class TypePropertieController extends Controller
{
    public function types_properties(){
        $types_properties = TypePropetie::all();
        return response()->json($types_properties);
    }
}
