<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    public function set(Tenant $tenant)
    {
        session(['tenant_id' => $tenant->id]);

        return back();
    }
    public function getTenantPrincipal(Request $request)
    {
        $tenantId = session('tenant_id');

        $tenant = Tenant::with('country')
            ->where('is_active', true)
            ->where('id', $tenantId)
            ->first();

        if (!$tenant) {
            return response()->json([], 200);
        }

        return response()->json([
            'id'           => $tenant->id,
            'name'         => $tenant->name,
            'legalName'    => $tenant->legalName,
            'taxId'        => $tenant->taxId,
            'email'        => $tenant->email,
            'phone'        => $tenant->phone,
            'address'      => $tenant->address,
            'country_id'   => $tenant->country_id,
            'latitude'     => $tenant->latitude,
            'longitude'     => $tenant->longitude,
            'is_principal'     => $tenant->is_principal,
            'tenant_created_id'     => $tenant->tenant_created_id,
            'logo' => $tenant->logo ? Storage::url($tenant->logo) : asset('images/lalux3.png'),
            'country_name' => optional($tenant->country)->name,
        ], 200);
    }

    public function getTenantBusiness()
    {
        $user = Auth::user()->id;
        $tenantCreatorId = session('tenant_id');

        $tenants = Tenant::with('country')
            ->where('user_id', $user)
            ->where('tenant_created_id', $tenantCreatorId)
            ->get();

        return response()->json(
            $tenants->map(function ($tenant) {
                return [
                    'id'           => $tenant->id,
                    'name'         => $tenant->name,
                    'legalName'    => $tenant->legalName,
                    'taxId'        => $tenant->taxId,
                    'email'        => $tenant->email,
                    'phone'        => $tenant->phone,
                    'address'      => $tenant->address,
                    'country_id'   => $tenant->country_id,
                    'latitude'     => $tenant->latitude,
                    'longitude'    => $tenant->longitude,
                    'is_principal'     => $tenant->is_principal,
                    'tenant_created_id'     => $tenant->tenant_created_id,
                    'logo'         => $tenant->logo
                        ? Storage::url($tenant->logo)
                        : asset('images/lalux3.png'),
                    'country_name' => optional($tenant->country)->name,
                ];
            }),
            200
        );
    }

    public function save(Request $request)
    {
        $session_tenant = session('tenant_id');

        $isUpdate = is_numeric($request->id) && $request->id > 0;
        $tenant = $isUpdate ? Tenant::find($request->id) : null;

        if ($isUpdate && !$tenant) {
            return response()->json([
                'message' => 'Tenant no encontrado'
            ], 404);
        }

        $emailRules = ['required', 'email'];
        if ($isUpdate) {
            $emailRules[] = Rule::unique('tenants', 'email')->ignore($tenant->id);
        } else {
            $emailRules[] = 'unique:tenants,email';
        }

        $taxRules = ['nullable', 'string'];
        if ($isUpdate) {
            $taxRules[] = Rule::unique('tenants', 'taxId')->ignore($tenant->id);
        } else {
            $taxRules[] = 'unique:tenants,taxId';
        }

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'legalName'  => 'required|string|max:255',
            'taxId' => $taxRules,
            'email'      => $emailRules,
            'phone'      => 'nullable|string|max:50',
            'address'    => 'nullable|string|max:500',
            'country_id' => 'required|exists:countries,id',
            'logo'       => 'nullable|image|max:2048',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
        ]);

        $validated['latitude']  = $request->latitude === 'undefined' ? null : $request->latitude;
        $validated['longitude'] = $request->longitude === 'undefined' ? null : $request->longitude;

        if ($request->has('is_principal')) {
            $validated['is_principal'] = filter_var($request->is_principal, FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->has('business_units') && filter_var($request->business_units, FILTER_VALIDATE_BOOLEAN)) {
            $validated['tenant_created_id'] = $session_tenant ?: null;
        }

        if ($isUpdate) {
            $tenant->update(array_merge($validated, ['user_id' => Auth::id()]));
        } else {
            $tenant = Tenant::create(array_merge($validated, ['user_id' => Auth::id()]));
        }

        if ($request->hasFile('logo')) {
            if ($tenant->logo && Storage::disk('public')->exists($tenant->logo)) {
                Storage::disk('public')->delete($tenant->logo);
            }

            $tenant->logo = $request->file('logo')->store('tenants', 'public');
            $tenant->save();
        }

        return response()->json([
            'message' => 'Configuración guardada correctamente',
            'tenant'  => [
                'id'           => $tenant->id,
                'name'         => $tenant->name,
                'legalName'    => $tenant->legalName,
                'taxId'        => $tenant->taxId,
                'email'        => $tenant->email,
                'phone'        => $tenant->phone,
                'address'      => $tenant->address,
                'country_id'   => $tenant->country_id,
                'country_name' => optional($tenant->country)->name,
                'latitude'     => $tenant->latitude,
                'longitude'    => $tenant->longitude,
                'logo'         => $tenant->logo,
                'is_principal' => $tenant->is_principal ?? 0,
            ]
        ]);
    }

}
