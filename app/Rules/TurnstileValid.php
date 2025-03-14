<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class TurnstileValid implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (config('services.turnstile.site_key') === null) {
            return;
        }

        if ($value === null) {
            $fail("The :attribute field is required.");
            return;
        }

        if (!Http::post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => config('services.turnstile.secret_key'),
            'response' => $value,
        ])->json('success')) {
            $fail("You might be a robot, try again.");
            return;
        }
    }
}
