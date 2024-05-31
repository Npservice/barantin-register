<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ppjk extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = ['id', 'create_at', 'updated_at'];
    protected $fillable = [
        'pj_baratin_id',
        'barantin_cabang_id',
        'nama_ppjk',
        'jenis_identitas_ppjk',
        'nomor_identitas_ppjk',
        'email_ppjk',
        'tanggal_kerjasama_ppjk',
        'alamat_ppjk',
        'master_negara_id',
        'master_provinsi_id',
        'master_kota_kab_id',

        'nama_cp_ppjk',
        'alamat_cp_ppjk',
        'telepon_cp_ppjk',

        'nama_tdd_ppjk',
        'jenis_identitas_tdd_ppjk',
        'nomor_identitas_tdd_ppjk',
        'jabatan_tdd_ppjk',
        'alamat_tdd_ppjk',
        'status_ppjk'
    ];

    public function baratin(): BelongsTo
    {
        return $this->belongsTo(PjBaratin::class, 'pj_baratin_id');
    }
    public function baratincabang(): BelongsTo
    {
        return $this->belongsTo(BarantinCabang::class, 'barantin_cabang_id');
    }

}
