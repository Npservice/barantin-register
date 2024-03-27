<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MitraPerusahaan extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = ['id', 'create_at', 'updated_at'];
    protected $fillable = [
        'pj_baratin_id',
        'barantin_cabang_id',
        'nama_mitra',
        'jenis_identitas_mitra',
        'nomor_identitas_mitra',
        'alamat_mitra',
        'telepon_mitra',
        'master_negara_id',
        'master_provinsi_id',
        'master_kota_kab_id',
    ];

    public function baratin(): BelongsTo
    {
        return $this->belongsTo(PjBaratin::class);
    }
    public function baratinCabang(): BelongsTo
    {
        return $this->belongsTo(BarantinCabang::class);
    }
    public function negara(): BelongsTo
    {
        return $this->belongsTo(MasterNegara::class);
    }

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(MasterProvinsi::class);
    }
    public function kotas(): BelongsTo
    {
        return $this->belongsTo(MasterKotaKab::class);
    }
}
