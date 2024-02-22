<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MasterUptRequestUpdate extends FormRequest
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
            'kode_satpel' => 'required',
            'kode_upt' => 'required',
            'nama' => 'required',
            'nama_en' => 'required',
            'wilayah_kerja' => 'required',
            'nama_satpel' => 'required',
            'kota' => 'required',
            'kode_pelabuhan' => 'required',
            'tembusan' => 'required',
            'otoritas_pelabuhan' => 'required',
            'syah_bandar_pelabuhan' => 'required',
            'kepala_kantor_bea_cukai' => 'required',
            'nama_pengelola' => 'required',
            'stat_ppkol' => 'required',
            'stat_insw' => 'required',
        ];
    }
}
