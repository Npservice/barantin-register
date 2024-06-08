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
    protected $update = false;
    public function __construct(string $identias, bool $update = false)
    {
        $this->identitas = $identias;
        $this->update = $update;

    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->identitas == 'induk') {
            $nomer = PjBarantin::where('nomor_identitas', $value)->value('nomor_identitas');
            if ($nomer) {
                if ($this->update && $nomer != $value) {
                    $fail("The {$attribute} has already been taken")->translate();
                } else {
                    return;
                }
                $fail("The {$attribute} has already been taken")->translate();
            }
        }
    }
}
