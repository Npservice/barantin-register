<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(MasterProvinsi::class);
    }
}
