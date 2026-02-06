<?php

namespace App\Http\Controllers;

use App\Models\PropertyFeature;
use Illuminate\Http\Request;

class PropertyFeatureController extends Controller
{
     public function index()
    {
        return PropertyFeature::select('id', 'name', 'icon', 'description')
            ->orderBy('name')
            ->get();

        // $property->features()->sync($request->features);

    }
}
