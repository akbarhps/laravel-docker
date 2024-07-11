<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserKeycloak extends Model
{
    use SoftDeletes;

    protected $table = 'user_keycloak';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $perPage = 15;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'sub',
        'nik',
        'user_group',
        'mobile',
        'full_name',
        'kode_identitas',
        'first_name',
        'last_name',
        'email',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function casts(): array
    {
        return [
            'user_group' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }
}
