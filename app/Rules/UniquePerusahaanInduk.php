<?php

namespace App\Rules;

use Closure;
use App\Models\PjBarantin;
use Illuminate\Contracts\Validation\ValidationRule;

class UniquePerusahaanInduk implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    protected $identitas;
    public function __construct(string $identias)
    {
        $this->identitas = $identias;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->identitas == 'induk') {
          $nomer=  PjBarantin::where('nomer_identitas', $value)->value('nomor_identitas');
          if($nomer){
                $fail("The {attribute} has already been taken")->translate();
          }
        }
    }
}
