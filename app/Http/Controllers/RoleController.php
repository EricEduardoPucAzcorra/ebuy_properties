<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function view(){
        $modules = Module::with(['permissions' => function ($q) {
                $q->orderBy('name');
            }])
            ->where('is_active', 1)
            ->where('clasification', 'admin')
            ->orderBy('id')
            ->get();

        $modules->transform(function($module) {

            $module->title_translated = __('modules.' . $module->name);

            $module->permissions->transform(function($perm) {
                $perm->title_translated = __('permissions.' . explode('.', $perm->name)[1]);
                return $perm;
            });

            return $module;
        });

        return view('roles.index', compact('modules'));
    }

    public function index(Request $request){
        $roles = Role::all();
        return response()->json($roles);
    }

    public function getRolePermissions(Role $role)
    {
        return $role->permissions()->pluck('permissions.id');
    }

    public function syncPermissions(Request $request, Role $role)
    {
        $hasRole = auth()->user()->hasRole($role->id);

        $role->permissions()->sync($request->permissions ?? []);
        return response()->json(['data' => true, 'hasrole'=> $hasRole]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'description'     => 'nullable|string|max:255'
        ]);

        $role = Role::create([
            'name'      => $validated['name'],
            'description'      => $validated['description']
        ]);

        return response()->json([
            'message' => __('general.roles.messages.role_created_success'),
            'role'    => $role
        ], 201);
    }

    public function update(Request $request)
    {
        $role = Role::find($request->id);

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'description'     => 'nullable|string|max:255'
        ]);

        $role->update($validated);

        return response()->json([
            'message' => __('general.roles.messages.role_updated_success'),
            'role'    => $role
        ], 200);
    }

    public function roles_select(){
        $roles = Role::all();
        return response()->json($roles);
    }
}
