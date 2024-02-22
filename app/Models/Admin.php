<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory, HasUuids;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $fillable = ['nama', 'email', 'username', 'password'];
    protected $hidden = [
        'password',

    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
