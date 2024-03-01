<?php

namespace App\Models;

use App\Models\PjBaratin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
    public function baratin(): HasOne
    {
        return $this->hasOne(PjBaratin::class);
    }
}
