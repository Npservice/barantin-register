<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterUpt extends Model
{
    use HasFactory, HasUuids;
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

}
