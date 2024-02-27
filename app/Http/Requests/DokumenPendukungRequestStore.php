<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DokumenPendukungRequestStore extends FormRequest
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
            'jenis_dokumen' => ['required', Rule::in('KTP', 'PASSPORT', 'NPWP')],
            'nomer_dokumen' => 'required|digits_between:1,16',
            'tanggal_terbit' => 'required|date',
            'file_dokumen' => 'required|file|mimes:png,jpg,pdf|max:5000'
        ];
    }
}
