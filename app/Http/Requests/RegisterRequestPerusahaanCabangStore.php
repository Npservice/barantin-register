<?php

namespace App\Http\Requests;

use App\Models\MasterUpt;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequestPerusahaanCabangStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_induk' => 'required|exists:pj_baratins,id',
            'upt' => [
                'required',
                function ($attribute, $value, $fail) {
                    $validUpts = MasterUpt::pluck('id')->toArray(); // Ganti 'id' dengan kolom yang sesuai dari model Anda
                    foreach ($value as $item) {
                        if (!in_array($item, $validUpts)) {
                            $fail('One or more selected upt is invalid.');
                        }
                    }
                },
            ],
            'nitku' => 'required|unique:barantin_cabangs,nitku|digits:6',
            'telepon' => 'required|regex:/^\d{4}-\d{4}-\d{4}$/',
            'nomor_fax' => 'required|regex:/^\(\d{3}\) \d{3}-\d{4}$/',

            'email' => 'required|exists:pre_registers,email',
            'lingkup_aktivitas' => [
                'required',
                function ($attribute, $value, $fail) {
                    $validValues = [1, 2, 3, 4]; // Daftar nilai yang valid
                    foreach ($value as $item) {
                        if (!in_array($item, $validValues)) {
                            $fail('One or more selected values is invalid.');
                        }
                    }
                },
            ],
            'nama_alias_perusahaan' => Rule::requiredIf(function () {
                $lingkup_aktivitas = request()->input('lingkup_aktivitas');
                return $lingkup_aktivitas && in_array(3, $lingkup_aktivitas);
            }),

            'status_import' => ['required', Rule::in([25, 26, 27, 28, 29, 30, 31, 32])],
            // 'negara' => 'required|exists:master_negaras,id',
            'kota' => 'required|exists:master_kota_kabs,id',
            'provinsi' => 'required|exists:master_provinsis,id',
            'alamat' => 'required',

            'nama_cp' => 'required',
            'alamat_cp' => 'required',
            'telepon_cp' => 'required|regex:/^\d{4}-\d{4}-\d{4}$/',

            'nama_tdd' => 'required',
            'jenis_identitas_tdd' => ['required', Rule::in(['KTP', 'NPWP', 'PASSPORT'])],
            'nomor_identitas_tdd' => [
                'required',
                'numeric',
                function ($attr, $val, $fail) {
                    $jenis_dokumen = request()->input('jenis_identitas_tdd');
                    if ($jenis_dokumen === 'KTP' || $jenis_dokumen === 'NPWP') {
                        if (!preg_match('/^[0-9]{16}$/', $val)) {
                            $fail('Nomor dokumen harus berupa 16 digit.');
                        }
                    }

                }
            ],
            'jabatan_tdd' => 'required',
            'alamat_tdd' => 'required',

        ];
    }
}
