<?php

namespace Corals\User\Traits;

trait TwoFactorAuthenticatable
{
    /**
     * Get the e-mail address used for two-factor authentication.
     *
     * @return string
     */
    public function getEmailForTwoFactorAuth()
    {
        return $this->email;
    }

    /**
     * Get the country code used for two-factor authentication.
     *
     * @return string
     */
    public function getAuthCountryCode()
    {
        return $this->phone_country_code;
    }

    /**
     * Get the phone number used for two-factor authentication.
     *
     * @return string
     */
    public function getAuthPhoneNumber()
    {
        return $this->phone_number;
    }

    public function getPhoneAttribute()
    {
        if ($this->phone_country_code && $this->phone_number) {
            return "({$this->phone_country_code}) {$this->phone_number}";
        } else {
            return null;
        }
    }

    /**
     * Set the country code and phone number used for two-factor authentication.
     *
     * @param string $countryCode
     * @param string $phoneNumber
     *
     * @return void
     */
    public function setAuthPhoneInformation($countryCode, $phoneNumber)
    {
        $this->phone_country_code = $countryCode;

        $this->phone_number = $phoneNumber;
    }

    /**
     * Get the two-factor provider options in array format.
     *
     * @return array
     */
    public function getTwoFactorAuthProviderOptions()
    {
        return json_decode($this->two_factor_options, true) ?: [];
    }

    /**
     * Set the two-factor provider options in array format.
     *
     * @param array $options
     *
     * @return void
     */
    public function setTwoFactorAuthProviderOptions(array $options)
    {
        $this->two_factor_options = json_encode($options);
    }

    /**
     * Determine if the user is using two-factor authentication.
     *
     * @return bool
     */
    public function getUsingTwoFactorAuthAttribute()
    {
        $options = $this->getTwoFactorAuthProviderOptions();

        return isset($options['id']);
    }
}
