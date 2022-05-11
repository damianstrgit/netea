<?php namespace App\Validators;

use Illuminate\Support\MessageBag;
use Illuminate\Validation\Validator;

class RestValidator extends Validator
{
    /**
     * Add an error message to the validator's collection of messages.
     *
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return void
     */
    public function addFailure($attribute, $rule, $parameters = [])
    {
        if (!$this->messages) {
            $this->passes();
        }

        $message = $this->getMessage($attribute, $rule);

        $message = $this->makeReplacements(
            $message,
            $attribute,
            $rule,
            $parameters
        );

        $customMessage = new MessageBag();

        $customMessage->merge([
            'code' => strtolower($attribute . '_' . $rule . '_error'),
        ]);
        $customMessage->merge(['message' => $message]);

        $this->messages->add($attribute, $customMessage);

        $this->failedRules[$attribute][$rule] = $parameters;
    }
}
