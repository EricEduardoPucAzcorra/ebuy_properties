<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_name',
        'second_last_name',
        'phone',
        'is_active',
        'profile',
        'profile_url',
        'google_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function permissions()
    {
        return Permission::whereHas('roles', function ($q) {
            $q->whereIn(
                'roles.id',
                $this->roles()->pluck('roles.id')
            );
        });
    }

   public function hasPermission(string $name): bool
    {
        return $this->permissions()
            ->where('permissions.name', $name)
            ->exists();
    }

    public function hasRole(int $roleId): bool
    {
        return DB::table('user_roles')
            ->where('user_id', $this->id)
            ->where('role_id', $roleId)
            ->exists();
    }

     public function getPermissionNames()
    {
        return $this->permissions()->pluck('name')->toArray();
    }

    public function hasRoleName(string $name): bool
    {
        return $this->roles()
            ->where('name', $name)
            ->exists();
    }


}
