<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarantinCabang extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $fillable = [
        'pre_register_id',
        'pj_baratin_id',
        'user_id',
        'kode_perusahaan',
        'password',
        'nama_perusahaan',
        'nama_alias_perusahaan',
        'jenis_identitas',
        'nomor_identitas',
        'nitku',
        'alamat',
        'kota',
        'telepon',
        'nama_cp',
        'alamat_cp',
        'telepon_cp',
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
        'provinsi_id',
        'status_import',
        'negara_id',
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

    public function register(): HasMany
    {
        return $this->hasMany(Register::class);
    }
    public function preRegister(): BelongsTo
    {
        return $this->belongsTo(PreRegister::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function baratinInduk(): BelongsTo
    {
        return $this->belongsTo(PjBaratin::class);
    }
    public function mitra(): HasMany
    {
        return $this->hasMany(MitraPerusahaan::class);
    }
    public function ppjk(): HasMany
    {
        return $this->hasMany(Ppjk::class);
    }

}
