<?php

namespace App\Models;

use App\Models\PjBaratin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterProvinsi extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode',
        'un_code',
        'nama',
        'nama_en',
    ];
    public function baratin(): HasMany
    {
        return $this->hasMany(PjBaratin::class, 'provinsi_id', 'id');
    }
    public function baratinCabang(): HasMany
    {
        return $this->hasMany(BarantinCabang::class, 'provinsi_id', 'id');
    }
    public function ppjk(): HasMany
    {
        return $this->hasMany(Ppjk::class, 'master_provinsi_id', 'id');
    }
    public function mitra(): HasMany
    {
        return $this->hasMany(MitraPerusahaan::class);
    }

}
