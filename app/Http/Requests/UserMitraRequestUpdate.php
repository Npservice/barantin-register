<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserMitraRequestUpdate extends FormRequest
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
            'nama_mitra' => 'required|string|max:255',
            'jenis_identitas_mitra' => ['required', Rule::in(['KTP', 'NPWP', 'PASSPORT'])],
            'nomor_identitas_mitra' => [
                'required',
                'numeric',
                function ($attr, $val, $fail) {
                    $jenis_dokumen = request()->input('jenis_identitas_mitra');
                    if ($jenis_dokumen === 'KTP' || $jenis_dokumen === 'NPWP') {
                        if (!preg_match('/^[0-9]{16}$/', $val)) {
                            $fail('Nomor dokumen harus berupa 16 digit.');
                        }
                    }

                }
            ],

            'alamat_mitra' => 'required|string|max:255',
            'telepon_mitra' => 'required|string|max:255',
            'negara' => 'required|exists:master_negaras,id',
            'provinsi' => 'required_if:master_negara_id,99|exists:master_provinsis,id',
            'kabupaten_kota' => 'required_if:negara,99|exists:master_kota_kabs,id',
        ];
    }
}
