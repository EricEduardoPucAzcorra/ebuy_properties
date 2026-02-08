<?php

namespace App\Http\Controllers;

use App\Models\FeatureCategory;
use App\Models\PropertyFeature;
use Illuminate\Http\Request;

class PropertyFeatureController extends Controller
{
    public function index()
    {
        return FeatureCategory::with(['features' => function ($q) {
            $q->select('id', 'name', 'description', 'icon', 'feature_category_id')
            ->orderBy('name');
        }])
        ->select('id', 'name', 'description', 'icon')
        ->orderBy('name')
        ->get();
    }

}
