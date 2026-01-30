<?php

namespace App\Http\Controllers;

use App\Models\Countrie;
use Illuminate\Http\Request;

class AutocompleteController extends Controller
{
    public function search_countries(Request $request)
    {
        $query = $request->get('q', '');

        $cities = Countrie::where('name', 'LIKE', "%{$query}%")
            ->orderBy('name')
            ->limit(10)
            ->get();

        return response()->json($cities);
    }
}
