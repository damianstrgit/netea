<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DateRFC3339 implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !(
            \DateTime::createFromFormat(\DateTime::RFC3339, $value) === false
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return [
            [
                'code' => strtolower(':attribute dateformat_error'),
                'message' =>
                    'The :attribute does not match the format RFC3339.',
            ],
        ];
    }
}
