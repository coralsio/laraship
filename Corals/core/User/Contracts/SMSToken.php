<?php

namespace Corals\User\Contracts;

interface SMSToken
{
    /**
     * Send the user two-factor authentication token via SMS.
     *
     * @param TwoFactorAuthenticatableContract $user
     *
     * @return void
     */
    public function sendSmsToken(TwoFactorAuthenticatableContract $user);
}
