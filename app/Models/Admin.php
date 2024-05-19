<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, HasUuids;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $fillable = ['uid', 'roles_id', 'apps_id', 'role_name', 'access_token', 'refresh_token', 'expiry', 'password'];
    protected $hidden = [
        'access_token',
        'password'

    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


}
