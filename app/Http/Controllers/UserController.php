<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function view(){
        return view('admin.users.index');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $dataTable = new UserDataTable();
            return response()->json($dataTable->getResponse($request));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:8|confirmed',
            'last_name' => 'nullable|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'phone'     => 'nullable|string|max:10',
            'is_active' => 'required|boolean',
            'profile'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            'roles'   => 'nullable|array',
            'roles.*'   => 'exists:roles,id',
        ]);

        if ($request->hasFile('profile')) {
            $path = $request->file('profile')->store('profiles', 'public');
            $validated['profile'] = $path;
        }

        $user = User::create([
            'name'      => $validated['name'],
            'last_name'      => $validated['last_name'],
            'second_last_name'      => $validated['second_last_name'],
            'phone'     => $validated['phone'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'is_active' => $validated['is_active'],
            'profile'   => $validated['profile'] ?? null,
        ]);

        // $user->roles()->sync($validated['roles']);

        $user->roles()->sync($request->input('roles', []));


        return response()->json([
            'message' => __('general.users.messages.user_created_success'),
            'user'    => $user
        ], 201);
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:8|confirmed',
            'last_name' => 'nullable|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'phone'     => 'nullable|string|max:10',
            'is_active' => 'required|boolean',
            'profile'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'roles'   => 'nullable|array',
            'roles.*'   => 'exists:roles,id',
        ]);

        if ($request->hasFile('profile')) {

            if ($user->profile && Storage::disk('public')->exists($user->profile)) {
                Storage::disk('public')->delete($user->profile);
            }

            $validated['profile'] = $request->file('profile')
                ->store('profiles', 'public');
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        $user->roles()->sync($request->input('roles', []));

        return response()->json([
            'message' => __('general.users.messages.user_updated_success'),
            'is_authenticated' => Auth::id() === $user->id,
            'user'    => $user
        ], 200);
    }

    public function update_state(Request $request)
    {
        $user = User::find($request->id);

        $validated = $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => __('general.users.messages.user_updated_success'),
            'user'    => $user
        ], 200);
    }

    public function profile_view(){
        return view('profile.index');
    }

    public function authenticatedUser()
    {
        $user = User::with('roles:id,name')
            ->where('id', Auth::id())
            ->firstOrFail();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'last_name' => $user->last_name,
            'second_last_name' => $user->second_last_name,
            'phone' => $user->phone,
            'email' => $user->email,
            'profile' => $user->profile,
            'profile_url' => $user->profile_url,
            'is_active' => $user->is_active,
            'created_at' => $user->created_at->format('d/m/Y'),
            'roles' => $user->roles->map(fn ($role) => [
                'id' => $role->id,
                'name' => $role->name,
            ])
        ]);
    }

}

