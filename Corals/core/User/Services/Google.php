<?php

namespace Corals\User\Services;

use Corals\User\Rules\GoogleTokenMatch;
use Exception;
use GuzzleHttp\Client as HttpClient;
use Corals\User\Contracts\TwoFactorAuthenticatableContract;
use Corals\User\Contracts\Provider as BaseProvider;
use Illuminate\Http\Request;

class Google implements BaseProvider
{
    /**
     * Array containing configuration data.
     *
     * @var array
     */
    private $config;
    public $supportedChannels = [];
    public $details_view = "";
    public $registration_view = "";

    public $token_view = "";

    /**
     * Authy constructor.
     */
    public function __construct()
    {
        $this->details_view = "User::users.2fa.google";
        $this->token_view = "User::users.2fa.google_token";
        $this->registration_view = "";
    }

    /**
     * Determine if the given user has two-factor authentication enabled.
     *
     * @param TwoFactorAuthenticatableContract $user
     *
     * @return bool
     */
    public function isEnabled(TwoFactorAuthenticatableContract $user)
    {
        $options = $user->getTwoFactorAuthProviderOptions();
        return $this->isRegistered($user) && isset($options['enabled']) && $options['enabled'];
    }

    public function isRegistered(TwoFactorAuthenticatableContract $user)
    {
        $options = $user->getTwoFactorAuthProviderOptions();
        return isset($options['token']);
    }

    /**
     * Register the given user with the provider.
     * @param TwoFactorAuthenticatableContract $user
     * @param Request $request
     * @return array
     */
    public function register(TwoFactorAuthenticatableContract $user, Request $request)
    {
        try {

            $options = [
                'token' => $request->get('google2fa_secret'),
                'channel' => 'authenticator'
            ];

            return $options;

        } catch (\Exception $exception) {
            log_exception($exception, Google::class, 'register');
        }
    }

    /**
     * Send the user two-factor authentication token via SMS.
     *
     * @param TwoFactorAuthenticatableContract $user
     *
     * @return boolean
     */
    public function sendAuthenticatorToken(TwoFactorAuthenticatableContract $user)
    {
        return true;
    }

    /**
     * Determine if the given token is valid for the given user.
     *
     * @param TwoFactorAuthenticatableContract $user
     * @param string $token
     *
     * @return bool
     */
    public function tokenIsValid(TwoFactorAuthenticatableContract $user, $token)
    {
        try {
            $options = $user->getTwoFactorAuthProviderOptions();
            $google2fa = app('pragmarx.google2fa');

            $result = $google2fa->verifyGoogle2FA($options['token'], $token);
            return $result;


        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Delete the given user from the provider.
     *
     * @param TwoFactorAuthenticatableContract $user
     *
     * @return void
     */
    public function delete(TwoFactorAuthenticatableContract $user)
    {
        $options = $user->getTwoFactorAuthProviderOptions();

        (new HttpClient())->post(
            $this->config['api_url'] . '/protected/json/users/delete/' .
            $options['id'] . '?api_key=' . $this->config['api_key']
        );

        $user->setTwoFactorAuthProviderOptions([]);
    }

    /**
     * Determine if the given user should be sent two-factor authentication token via SMS/phone call.
     *
     * @param TwoFactorAuthenticatableContract $user
     *
     * @return bool
     */
    public function canSendToken(TwoFactorAuthenticatableContract $user)
    {
        return false;
    }

    public function profileValidation($params, $user)
    {
        if (!$this->isEnabled($user) && $params['two_factor_auth_enabled']) {
            return [
                'activation_token' => ['required', new GoogleTokenMatch($params['google2fa_secret'])]
            ];
        }
        return [];

    }

    public function registrationValidation($params)
    {
        return [];
    }

}
