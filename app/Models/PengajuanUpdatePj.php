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
        'pj_barantin_id',
        'keterangan',
        'update_token',
        'expire_at',
        'persetujuan',
        'status_update'
    ];

    public function barantin(): BelongsTo
    {
        return $this->belongsTo(PjBarantin::class, 'pj_barantin_id', 'id');
    }
}
