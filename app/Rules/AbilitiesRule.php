<?php

namespace App\Rules;

use App\Support\Ability;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AbilitiesRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = array_values((array) $value);

        foreach ($value as $ability) {
            if (!in_array($ability, Ability::grouped())) {
                $fail('„' . $ability . '“ is not a valid ability.');
            }
        }
    }
}
