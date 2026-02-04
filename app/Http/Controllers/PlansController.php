<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlansController extends Controller
{
    public function plans_view()
    {
        return view('plans.index');
    }
    public function index()
    {
        return Plan::with('features')->orderBy('price')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'price'       => 'required|numeric|min:0',
            'is_featured' => 'boolean',
            'is_active'   => 'boolean',
            'features'    => 'nullable|array',
            'features.*.feature_plan_id' => 'required|exists:feature_plans,id',
            'features.*.mount' => 'nullable|integer',
            'features.*.description' => 'nullable|string',
            'features.*.other_description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($data, &$plan) {

            $features = $data['features'] ?? [];
            unset($data['features']);

            $plan = Plan::create($data);

            foreach ($features as $feature) {
                $plan->features()->attach(
                    $feature['feature_plan_id'],
                    [
                        'mount' => $feature['mount'] ?? null,
                        'description' => $feature['description'] ?? null,
                        'other_description' => $feature['other_description'] ?? null,
                    ]
                );
            }
        });

        return $plan->load('features');
    }


    public function update(Request $request, Plan $plan)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'price'       => 'required|numeric|min:0',
            'is_featured' => 'boolean',
            'is_active'   => 'boolean',
            'features'    => 'nullable|array',
            'features.*.feature_plan_id' => 'required|exists:feature_plans,id',
            'features.*.mount' => 'nullable|integer',
            'features.*.description' => 'nullable|string',
            'features.*.other_description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($plan, $data) {

            $features = $data['features'] ?? [];
            unset($data['features']);

            $plan->update($data);

            $syncData = [];
            foreach ($features as $feature) {
                $syncData[$feature['feature_plan_id']] = [
                    'mount' => $feature['mount'] ?? null,
                    'description' => $feature['description'] ?? null,
                    'other_description' => $feature['other_description'] ?? null,
                ];
            }

            $plan->features()->sync($syncData);
        });

        return $plan->load('features');
    }


    public function destroy(Plan $plan)
    {
        $plan->delete();
        return response()->noContent();
    }

}
