<?php

namespace App\Models;

use App\Traits\Model\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory, HasCompositePrimaryKey;

    protected $table = 'user_roles';
    protected $primaryKey = ['user_id', 'role_id'];
    protected $keyType = 'string';
    protected $perPage = 15;

    public $incrementing = false;

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'role_id',
    ];

    protected $hidden = [
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }
}
