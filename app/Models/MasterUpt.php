<?php

namespace App\Models;

use App\Models\Register;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MasterUpt extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'updated_at', 'created_at'];
    protected $fillable = [
        'kode_satpel',
        'kode_upt',
        'nama',
        'nama_en',
        'wilayah_kerja',
        'nama_satpel',
        'kota',
        'kode_pelabuhan',
        'tembusan',
        'otoritas_pelabuhan',
        'syah_bandar_pelabuhan',
        'kepala_kantor_bea_cukai',
        'nama_pengelola',
        'stat_ppkol',
        'stat_insw',
    ];

    public function register(): HasMany
    {
        return $this->hasMany(Register::class, 'master_upt_id', 'id');
    }
}
