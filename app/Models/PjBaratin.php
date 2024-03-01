<?php

namespace App\Models;

use App\Models\MasterNegara;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PjBaratin extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = ['id', 'create_at', 'updated_at'];
    protected $fillable = [
        'kode_perusahaan',
        'pre_register_id',
        'password',
        'nama_perusahaan',
        'jenis_identitas',
        'nomor_identitas',
        'alamat',
        'telepon',
        'nama_cp',
        'alamat_cp',
        'telepon_cp',

        'kota',
        'provinsi_id',
        'negara_id',

        'nama_tdd',
        'jenis_identitas_tdd',
        'nomor_identitas_tdd',
        'jabatan_tdd',
        'alamat_tdd',

        'nama_pendaftar',
        'ktp_pendaftar',
        'jenis_perusahaan',

        'kontak_ppjk',
        'email',
        'fax',

        'kecamatan_id',

        'status_import',
        'status',

        'is_active',
        'status_prioritas',
    ];

    public function negara(): BelongsTo
    {
        return $this->belongsTo(MasterNegara::class, 'negara_id', 'id');
    }
    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(MasterProvinsi::class, 'provinsi_id', 'id');
    }
    public function kotas(): BelongsTo
    {
        return $this->belongsTo(MasterKotaKab::class, 'kota', 'id');
    }
    public function register(): BelongsToMany
    {
        return $this->belongsToMany(Register::class, 'registers', 'pj_barantin_id', 'master_upt_id', 'id', 'id');
    }
    public function preRegister(): BelongsTo
    {
        return $this->belongsTo(PreRegister::class, 'pre_register_id', 'id');
    }

}
