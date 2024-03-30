<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserPppjkRequestStore extends FormRequest
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
            'nama_ppjk' => 'required|string|max:255',
            'email_ppjk' => 'required|email|max:255',
            'tanggal_kerjasama_ppjk' => 'required|date',
            'alamat_ppjk' => 'required|string|max:255',

            'jenis_identitas_ppjk' => ['required', Rule::in(['KTP', 'NPWP', 'PASSPORT'])],
            'nomor_identitas_ppjk' => [
                'required',
                'numeric',
                function ($attr, $val, $fail) {
                    $jenis_dokumen = request()->input('jenis_identitas_ppjk');
                    if ($jenis_dokumen === 'KTP' || $jenis_dokumen === 'NPWP') {
                        if (!preg_match('/^[0-9]{16}$/', $val)) {
                            $fail('Nomor dokumen harus berupa 16 digit.');
                        }
                    }

                }
            ],

            'provinsi' => 'required|exists:master_provinsis,id',
            'kabupaten_kota' => 'required|exists:master_kota_kabs,id',

            'nama_cp_ppjk' => 'required|string|max:255',
            'alamat_cp_ppjk' => 'required|string|max:255',
            'telepon_cp_ppjk' => 'required|regex:/^\d{4}-\d{4}-\d{4}$/',

            'nama_tdd_ppjk' => 'required|string|max:255',

            'jenis_identitas_tdd_ppjk' => ['required', Rule::in(['KTP', 'NPWP', 'PASSPORT'])],
            'nomor_identitas_tdd_ppjk' => [
                'required',
                'numeric',
                function ($attr, $val, $fail) {
                    $jenis_dokumen = request()->input('jenis_identitas_tdd_ppjk');
                    if ($jenis_dokumen === 'KTP' || $jenis_dokumen === 'NPWP') {
                        if (!preg_match('/^[0-9]{16}$/', $val)) {
                            $fail('Nomor dokumen harus berupa 16 digit.');
                        }
                    }

                }
            ],

            'jabatan_tdd_ppjk' => 'required|string|max:255',
            'alamat_tdd_ppjk' => 'required|string|max:255',
        ];
    }
}
