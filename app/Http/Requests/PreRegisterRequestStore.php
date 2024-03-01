<?php

namespace App\Http\Requests;

use App\Models\PreRegister;
use App\Models\PjBaratanKpp;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PreRegisterRequestStore extends FormRequest
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
            'pemohon' => ['required', Rule::in(['perorangan', 'perusahaan'])],
            'nama' => 'required|max:255',
            'email' => [
                'required',
                'email',
                'max:50',
                function ($attr, $val, $fail) {
                    $cek = PreRegister::where('email', $val)->first();
                    if ($cek) {
                        if ($cek->status === 'MENUNGGU') {
                            $fail('Data email sudah terdaftar dan sedang diproses');
                        }
                        if ($cek->status === 'DISETUJUI') {
                            $fail('Data email sudah terdaftar dan disetujui silahkan login');
                        }
                        if ($cek->status === 'DITOLAK') {
                            $cek->update(['status' => null, 'verify_email' => null]);
                        }
                        if (!$cek->status) {
                            $fail('Data email sudah terdaftar silahkan hubungi Cs untuk menghapus email');
                        }

                    }


                },
                function ($attr, $val, $fail) {
                    $cek = PjBaratanKpp::where('email', $val)->first();
                    if ($cek) {
                        $fail('email sudah terdaftar di system lama kami silahkan register ulang');
                    }

                }
            ]
        ];
    }
}
