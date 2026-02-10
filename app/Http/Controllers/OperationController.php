<?php

namespace App\Http\Controllers;

use App\Models\TypeOperation;
use Illuminate\Http\Request;

class OperationController extends Controller
{
   public function operation_types()
    {
        $operations = TypeOperation::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => auto_trans($item->name),
                'slug' =>$item->slug,
                'description' => auto_trans($item->description),
                'image_icon' =>$item->image_icon,
                'is_active' =>$item->is_active
            ];
        });

        return response()->json($operations);
    }

}
