<?php

namespace App\Models;

use App\Models\PjBaratin;
use App\Models\MasterProvinsi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterKotaKab extends Model
{
    use HasFactory;
    protected $fillable = [
        'provinsi_id',
        'kode_kota_kab',
        'nama',
    ];

    // Define the relationship to Provinsi model
    public function provinsi()
    {
        return $this->belongsTo(MasterProvinsi::class, 'provinsi_id', 'id');
    }
    public function baratin(): HasMany
    {
        return $this->hasMany(PjBaratin::class, 'kota', 'id');
    }

    public function baratinCabang(): HasMany
    {
        return $this->hasMany(BarantinCabang::class, 'kota', 'id');
    }
    public function ppjk(): HasMany
    {
        return $this->hasMany(Ppjk::class);
    }
    public function mitra(): HasMany
    {
        return $this->hasMany(MitraPerusahaan::class);
    }


}
