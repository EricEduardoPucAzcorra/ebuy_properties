<?php

namespace App\Http\Controllers;

use App\Models\PropertyAttributeDefault;
use Illuminate\Http\Request;

class PropertyAttributeController extends Controller
{
    public function defaults()
    {
        return PropertyAttributeDefault::orderBy('key')->get();
    }
}
