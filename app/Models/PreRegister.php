<?php

namespace App\Models;

use App\Models\Register;
use App\Models\PjBaratin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'jenis_perusahaan',
        'pj_baratin_id'
    ];

    public function baratin(): HasOne
    {
        return $this->hasOne(PjBaratin::class);
    }
    public function baratinCabang(): HasOne
    {
        return $this->hasOne(BarantinCabang::class);
    }
    public function register(): HasMany
    {
        return $this->hasMany(Register::class, 'pre_register_id', 'id');
    }
}
