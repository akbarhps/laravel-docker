<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, CanResetPassword, Notifiable, SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $perPage = 15;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function roles(): HasManyThrough
    {
        return $this->hasManyThrough(Role::class, UserRole::class,
            'user_id', 'id', 'id', 'role_id');
    }

    public function hasRole(string $role): bool
    {
        foreach ($this->roles as $userRole) {
            if (strtoupper($userRole->id) === strtoupper($role)) {
                return true;
            }
        }

        return false;
    }

    public function isRegisteredViaKeycloak(): bool
    {
        return $this->password == null;
    }

    public function isValidPassword($password): bool
    {
        return Hash::check($password, $this->password);
    }
}
