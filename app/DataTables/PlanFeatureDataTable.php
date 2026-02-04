<?php

namespace App\DataTables;
use App\Models\FeaturePlan;
class PlanFeatureDataTable
{
    public function getResponse($request)
    {
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->get('search')['value'] ?? '';
        $status = $request->get('is_active');

        $query = FeaturePlan::with('plans');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($status !== null && $status !== '') {
            $query->where('is_active', $status);
        }

        $total = FeaturePlan::count();
        $filtered = $query->count();

        $features = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($features as $feature) {
            $data[] = [
                'id' => $feature->id,
                'name' => $feature->name,
                'descripcion' => $feature->descripcion,
                'is_active'=>$feature->is_active
            ];
        }

        return [
            'draw' => (int)$draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data
        ];
    }
}
