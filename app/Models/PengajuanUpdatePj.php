<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanUpdatePj extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $fillable = [
        'pj_baratin_id',
        'barantin_cabang_id',
        'keterangan',
        'update_token',
        'expire_at',
        'persetujuan',
        'status_update'
    ];

    public function baratin(): BelongsTo
    {
        return $this->belongsTo(PjBaratin::class, 'pj_baratin_id', 'id');
    }

    public function barantincabang(): BelongsTo
    {
        return $this->belongsTo(BarantinCabang::class, 'barantin_cabang_id', 'id');
    }

}
