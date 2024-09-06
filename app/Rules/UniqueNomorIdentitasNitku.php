<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueNomorIdentitasNitku implements ValidationRule
{
    protected $nomorIdentitas;
    protected $nitku;

    public function __construct($nomorIdentitas, $nitku)
    {
        $this->nomorIdentitas = $nomorIdentitas;
        $this->nitku = $nitku;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = DB::table('pj_barantins')
        ->join('registers', 'pj_barantins.id', '=', 'registers.pj_barantin_id')
        ->where('pj_barantins.nomor_identitas', $this->nomorIdentitas)
        ->where('pj_barantins.nitku', $this->nitku)
        ->where('registers.status', '!=', 'DITOLAK')
        ->exists();

        if ($exists) {
            $fail('The combination of NPWP and NITKU must be unique.')->translate();
        }
    }
}
