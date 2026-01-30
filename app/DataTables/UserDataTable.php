<?php

namespace App\DataTables;
use App\Models\User;
class UserDataTable
{
    public function getResponse($request)
    {
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->get('search')['value'] ?? '';
        $status = $request->get('is_active');

        // $query = User::query();
        $query = User::with('roles:id,name');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status !== null && $status !== '') {
            $query->where('is_active', $status);
        }

        $total = User::count();
        $filtered = $query->count();

        $users = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->id,
                'name' => $user->name,
                'last_name' => $user->last_name,
                'second_last_name' => $user->second_last_name,
                'phone' => $user->phone,
                'email' => $user->email,
                'profile' => $user->profile,
                'is_active' => $user->is_active,
                'created_at' => $user->created_at->format('d/m/Y'),
                'roles' => $user->roles->map(fn ($role) => [
                    'id' => $role->id,
                    'name' => $role->name,
                ])
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
