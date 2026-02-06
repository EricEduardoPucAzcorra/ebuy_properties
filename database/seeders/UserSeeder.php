<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    //
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Administrador',
                'is_active' => true,
                'profile'=>'',
                'password' => Hash::make('123456789')
            ]
        );

        $adminRole = Role::where('name', 'Admin')->first();
        $user->roles()->syncWithoutDetaching($adminRole->id);
    }
}
