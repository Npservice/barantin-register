<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterNegara extends Model
{
    use HasFactory;
    protected $fillable = ['kode', 'nama', 'nama_en'];
    public function baratin(): HasMany
    {
        return $this->hasMany(PjBaratin::class, 'negara_id', 'id');
    }
    public function baratinCabang(): HasMany
    {
        return $this->hasMany(BarantinCabang::class, 'negara_id', 'id');
    }
    public function ppjk(): HasMany
    {
        return $this->hasMany(Ppjk::class, 'master_negara_id', 'id');
    }
    public function mitra(): HasMany
    {
        return $this->hasMany(MitraPerusahaan::class);
    }

}
