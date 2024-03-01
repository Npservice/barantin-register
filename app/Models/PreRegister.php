<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PreRegister extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $fillable = [
        'pemohon',
        'nama',
        'email',
        'verify_email',
        'status'
    ];

    public function baratin(): HasOne
    {
        return $this->hasOne(PjBaratin::class);
    }
}
