<?php

namespace App\Http\Controllers;

use App\DataTables\PlanFeatureDataTable;
use App\Models\FeaturePlan;
use Illuminate\Http\Request;

class FeaturePlanController extends Controller
{
    public function index(){
        $features_plan = FeaturePlan::where('is_active', true)->get();
        return $features_plan;
    }

    public function view(){
        return view('plans_features.index');
    }

    public function index_data(Request $request)
    {
        if ($request->ajax()) {
            $dataTable = new PlanFeatureDataTable();
            return response()->json($dataTable->getResponse($request));
        }
    }

     public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'descripcion'     => 'nullable|string|max:255',
            'is_active' => 'required|boolean'
        ]);

        $feature = FeaturePlan::create([
            'name'      => $validated['name'],
            'descripcion'      => $validated['descripcion'],
            'is_active' => $validated['is_active'],
        ]);

        return response()->json([
            'message' => __('general.plan_feature.messages.feature_created_success'),
            'feature'    => $feature
        ], 201);
    }

    public function update(Request $request)
    {
        $feature = FeaturePlan::find($request->id);

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'descripcion'     => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $feature->update($validated);

        return response()->json([
            'message' => __('general.plan_feature.messages.feature_updated_success'),
            'feature'    => $feature
        ], 200);
    }

    public function update_state(Request $request)
    {
        $feature = FeaturePlan::find($request->id);

        $validated = $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $feature->update($validated);

        return response()->json([
            'message' => __('general.plan_feature.messages.feature_updated_success'),
            'feature'    => $feature
        ], 200);
    }

}
