<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Openpay\Data\Openpay;
use Exception;

class PlansController extends Controller
{
    private function getOpenpayInstance(Request $request = null)
    {
        $isProduction = config('app.env') === 'production';
        $clientIp = '187.189.155.42';
        
        if ($request) {
            $clientIp = $request->ip();
            if (!$isProduction && in_array($clientIp, ['127.0.0.1', '::1', ''])) {
                $clientIp = '187.189.155.42';
            }
        }
        
        return Openpay::getInstance(
            env('OPENPAY_MERCHANT_ID'),
            env('OPENPAY_PRIVATE_KEY'),
            'MX',
            $clientIp,
            $isProduction
        );
    }
    
    public function plans_view()
    {
        return view('admin.plans.index');
    }
    
    public function index()
    {
        return Plan::with('features')->orderBy('price')->get();
    }

    public function store(Request $request)
    {
        $openpay = $this->getOpenpayInstance($request);

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

        $plan = null;

        DB::transaction(function () use ($data, $openpay, &$plan) {
            $planData = [
                'name' => $data['name'],
                'amount' => (float) $data['price'],
                'currency' => 'MXN',
                'repeat_every' => 1,
                'repeat_unit' => 'month',
                'trial_days' => 0,
                'retry_times' => 2,
                'status_after_retry' => 'cancelled'
            ];

            if (!empty($data['description'])) {
                $planData['description'] = $data['description'];
            }

            try {
                $openpayPlan = $openpay->plans->add($planData);
                $openpayPlanId = $openpayPlan->id;
            } catch (Exception $e) {
                throw new Exception('Error al crear el plan en Openpay: ' . $e->getMessage());
            }

            $features = $data['features'] ?? [];
            unset($data['features']);
            $data['openpay_plan_id'] = $openpayPlanId;
            
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

        return response()->json($plan->load('features'), 201);
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

        $oldPrice = $plan->price;
        $newPrice = $data['price'];
        $priceChanged = $oldPrice != $newPrice;

        if ($priceChanged) {
            $openpay = $this->getOpenpayInstance($request);

            $planData = [
                'name' => $data['name'],
                'amount' => (float) $newPrice,
                'currency' => 'MXN',
                'repeat_every' => 1,
                'repeat_unit' => 'month',
                'trial_days' => 0,
                'retry_times' => 2,
                'status_after_retry' => 'cancelled'
            ];

            if (!empty($data['description'])) {
                $planData['description'] = $data['description'];
            }

            try {
                $openpayPlan = $openpay->plans->add($planData);
                $newOpenpayPlanId = $openpayPlan->id;
                
                DB::transaction(function () use ($plan, $newOpenpayPlanId, $data) {
                    $features = $data['features'] ?? [];
                    unset($data['features']);
                    
                    $plan->update([
                        'name' => $data['name'],
                        'description' => $data['description'],
                        'price' => $data['price'],
                        'is_featured' => $data['is_featured'] ?? false,
                        'is_active' => $data['is_active'] ?? true,
                        'openpay_plan_id' => $newOpenpayPlanId
                    ]);
                    
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
                
                return response()->json($plan->load('features'));
                
            } catch (Exception $e) {
                return response()->json([
                    'error' => true,
                    'message' => 'Error al actualizar el plan en Openpay: ' . $e->getMessage()
                ], 400);
            }
        } else {
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
            
            return response()->json($plan->load('features'));
        }
    }

    public function destroy(Plan $plan)
    {
        DB::transaction(function () use ($plan) {
            $plan->delete();
        });
        
        return response()->noContent();
    }
}