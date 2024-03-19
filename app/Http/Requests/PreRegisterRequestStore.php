<?php

namespace App\Http\Requests;

use App\Models\Register;
use App\Models\MasterUpt;
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
            'upt' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    $validUpts = MasterUpt::pluck('id')->toArray(); // Ganti 'id' dengan kolom yang sesuai dari model Anda
                    foreach ($value as $item) {
                        if (!in_array($item, $validUpts)) {
                            $fail('One or more selected upt is invalid.');
                        }
                    }
                },
            ],
            'pemohon' => ['required', Rule::in(['perorangan', 'perusahaan'])],
            'nama' => 'required|max:255',
            'email' => [
                'required',
                'email',
                'max:50',
                function ($attr, $val, $fail) {
                    /* ambil id user bedasarkan email */
                    $preregister = PreRegister::with('register:id,master_upt_id,pre_register_id')->where('email', $val)->first();
                    foreach ($preregister->register as $key => $value) {
                        foreach (request()->input('upt') as $in => $upt) {
                            if (in_array($upt, $preregister->register->pluck('master_upt_id')->all())) {
                                $register = Register::where('id', $value->id)->where('master_upt_id', $upt)->first();

                                if (isset ($register->status) && $register->status === 'MENUNGGU') {
                                    $fail('email sudah terdaftar di upt yang dipilih status menunggu');
                                }
                                if (isset ($register->status) && $register->status === 'DISETUJUI') {
                                    $fail('email sudah terdaftar di upt yang dipilih status disetujui');
                                }
                            }

                        }
                    }
                }
            ]
        ];
    }
}
