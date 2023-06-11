<?php

namespace Corals\User\Contracts;

use Illuminate\Http\Request;

interface Provider
{
    /**
     * Determine if the given user has two-factor authentication enabled.
     *
     * @param TwoFactorAuthenticatableContract $user
     *
     * @return bool
     */
    public function isEnabled(TwoFactorAuthenticatableContract $user);

    /**
     * Register the given user with the provider.
     *
     * @param TwoFactorAuthenticatableContract $user
     * @param Request $request
     *
     * @return null|array
     */
    public function register(TwoFactorAuthenticatableContract $user, Request $request);

    /**
     * Determine if the given token is valid for the given user.
     *
     * @param TwoFactorAuthenticatableContract $user
     * @param string $token
     *
     * @return bool
     */
    public function tokenIsValid(TwoFactorAuthenticatableContract $user, $token);

    /**
     * Delete the given user from the provider.
     *
     * @param TwoFactorAuthenticatableContract $user
     *
     * @return bool
     */
    public function delete(TwoFactorAuthenticatableContract $user);
}
