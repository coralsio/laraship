<?php

namespace Corals\User\Rules;

use Illuminate\Contracts\Validation\Rule;

class GoogleTokenMatch implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        try {
            $google2fa = app('pragmarx.google2fa');

            $result = $google2fa->verifyGoogle2FA($this->token, $value);
            return $result;


        } catch (Exception $e) {
            return false;
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Token provided is not valid';
    }
}