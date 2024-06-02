<?php

namespace App\Rules;

use App\Models\PengajuanUpdatePj;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class KeteranganUpdateRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $data = PengajuanUpdatePj::where('status_update', 'proses')->where(function ($query) {
            $query->where('pj_baratin_id', auth()->user()->baratin->id ?? null)->orWhere('barantin_cabang_id', auth()->user()->baratincabang->id ?? null);
        })->exists();
        if ($data) {
            $fail('Anda sudah mengajukan update data.');
        }

    }
}
