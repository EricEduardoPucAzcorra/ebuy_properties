<?php

namespace App\Http\Controllers;

use App\Models\TypeOperation;
use Illuminate\Http\Request;

class OperationController extends Controller
{
    public function operation_types(){
        $operatios = TypeOperation::all();
        return response()->json($operatios);
    }
}
