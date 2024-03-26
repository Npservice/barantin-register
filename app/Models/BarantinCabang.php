<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
