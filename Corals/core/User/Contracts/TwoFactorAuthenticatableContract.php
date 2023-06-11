<?php

namespace Corals\User\Contracts;

use Illuminate\Contracts\Auth\Authenticatable as BaseAuthenticatable;

interface TwoFactorAuthenticatableContract extends BaseAuthenticatable
{
    /**
     * Get the e-mail address used for two-factor authentication.
     *
     * @return string
     */
    public function getEmailForTwoFactorAuth();

    /**
     * Get the country code used for two-factor authentication.
     *
     * @return string
     */
    public function getAuthCountryCode();

    /**
     * Get the phone number used for two-factor authentication.
     *
     * @return string
     */
    public function getAuthPhoneNumber();

    /**
     * Set the country code and phone number used for two-factor authentication.
     *
     * @param string $countryCode
     * @param string $phoneNumber
     *
     * @return void
     */
    public function setAuthPhoneInformation($countryCode, $phoneNumber);

    /**
     * Get the two-factor provider options in array format.
     *
     * @return array
     */
    public function getTwoFactorAuthProviderOptions();

    /**
     * Set the two-factor provider options in array format.
     *
     * @param array $options
     *
     * @return void
     */
    public function setTwoFactorAuthProviderOptions(array $options);
}
