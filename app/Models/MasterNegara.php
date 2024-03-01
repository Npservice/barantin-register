<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterNegara extends Model
{
    use HasFactory;
    protected $fillable = ['kode', 'nama', 'nama_en'];
    public function baratin(): HasOne
    {
        return $this->hasOne(PjBaratin::class);
    }
}
