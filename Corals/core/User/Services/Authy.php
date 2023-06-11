<?php

namespace Corals\User\Services;

use Exception;
use GuzzleHttp\Client as HttpClient;
use Corals\User\Contracts\TwoFactorAuthenticatableContract;
use Corals\User\Contracts\PhoneToken as SendPhoneTokenContract;
use Corals\User\Contracts\Provider as BaseProvider;
use Corals\User\Contracts\SMSToken as SendSMSTokenContract;
use Illuminate\Http\Request;

class Authy implements BaseProvider, SendSMSTokenContract, SendPhoneTokenContract
{
    /**
     * Array containing configuration data.
     *
     * @var array
     */
    private $config;
    public $supportedChannels = [];
    public $details_view = "";
    public $token_view = "";

    /**
     * Authy constructor.
     */
    public function __construct()
    {
        if (!empty(config('authy.mode')) && (config('authy.mode') == 'sandbox')) {
            $this->config['api_key'] = config('authy.sandbox.key');
            $this->config['api_url'] = 'http://sandbox-api.authy.com';
        } else {
            $this->config['api_key'] = config('authy.live.key');
            $this->config['api_url'] = 'https://api.authy.com';
        }
        $this->supportedChannels = config('authy.supported_channels', []);
        $this->details_view = "User::users.2fa.authy";
        $this->token_view = "User::users.2fa.authy_token";
        $this->registration_view = "User::users.2fa.authy_register";

        $this->config['default_channel'] = config('authy.default_channel', 'sms');
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
        return isset($options['id']);
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


            $response = json_decode((new HttpClient())->post($this->config['api_url'] . '/protected/json/users/new?api_key=' . $this->config['api_key'], [
                'form_params' => [
                    'user' => [
                        'email' => $user->getEmailForTwoFactorAuth(),
                        'cellphone' => preg_replace('/[^0-9]/', '', $user->getAuthPhoneNumber()),
                        'country_code' => $user->getAuthCountryCode(),
                    ],
                ],
            ])->getBody(), true);

            $options = [
                'id' => $response['user']['id'],
                'channel' => $request->input('channel') ?? $this->config['default_channel'],
            ];

            $user->setAuthPhoneInformation($request->input('phone_country_code', ''), $request->input('phone_number', ''));

            return $options;
        } catch (\Exception $exception) {
            log_exception($exception, Authy::class, 'register');
        }
    }

    /**
     * Send the user two-factor authentication token via SMS.
     *
     * @param TwoFactorAuthenticatableContract $user
     *
     * @return boolean
     */
    public function sendSmsToken(TwoFactorAuthenticatableContract $user)
    {
        try {
            $options = $user->getTwoFactorAuthProviderOptions();

            $response = json_decode((new HttpClient())->get(
                $this->config['api_url'] . '/protected/json/sms/' . $options['id'] .
                '?force=true&api_key=' . $this->config['api_key']
            )->getBody(), true);

            return $response['success'] === true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Start the user two-factor authentication via phone call.
     *
     * @param TwoFactorAuthenticatableContract $user
     *
     * @return boolean
     */
    public function sendPhoneCallToken(TwoFactorAuthenticatableContract $user)
    {
        try {
            $options = $user->getTwoFactorAuthProviderOptions();

            $response = json_decode((new HttpClient())->get(
                $this->config['api_url'] . '/protected/json/call/' . $options['id'] .
                '?force=true&api_key=' . $this->config['api_key']
            )->getBody(), true);

            return $response['success'] === true;
        } catch (Exception $e) {
            return false;
        }
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

            $response = json_decode((new HttpClient())->get(
                $this->config['api_url'] . '/protected/json/verify/' .
                $token . '/' . $options['id'] . '?force=true&api_key=' .
                $this->config['api_key']
            )->getBody(), true);

            return $response['token'] === 'is valid';
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

        $options = $user->getTwoFactorAuthProviderOptions();
        $channel = $options['channel'];

        return ($this->isEnabled($user) && in_array($channel, ['sms', 'phoneCall'])) ? true : false;
    }

    public function registrationValidation($params)
    {
        return [
            'phone_country_code' => 'required',
            'phone_number' => 'required|unique:users',
        ];
    }

    public function profileValidation($params, $user)
    {
        return [
            'phone_country_code' => 'required',
            'phone_number' => 'required|unique:users',
        ];
    }
}
