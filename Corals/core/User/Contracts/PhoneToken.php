<?php

namespace Corals\User\Contracts;


interface PhoneToken
{
    /**
     * Start the user two-factor authentication via phone call.
     *
     * @param TwoFactorAuthenticatableContract $user
     *
     * @return void
     */
    public function sendPhoneCallToken(TwoFactorAuthenticatableContract $user);
}
