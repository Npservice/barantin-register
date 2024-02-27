<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PjBaratin extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = ['id', 'create_at', 'updated_at'];
    protected $fillable = [
        'kode_perusahaan',
        'password',
        'nama_perusahaan',
        'jenis_identitas',
        'nomor_identitas',
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
        'negara_id',
        'status_import',
        'status',

        'is_active',
        'status_prioritas',
    ];

}
