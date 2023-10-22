<?php

namespace App\Rules;

use App\Models\Currency;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ExistsActiveKeyCurrency implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $currency = Currency::query()->where('key', $value)->first();
        if (!$currency) {
            $fail('currency.validations.selected_currency_is_not_active')->translate();
        }
    }
}
