<?php

namespace Corals\User\Services;

use Corals\Foundation\Services\BaseServiceClass;
use Corals\User\Http\Requests\ProfileRequest;
use Corals\User\Facades\TwoFactorAuth;

class ProfileService extends BaseServiceClass
{
    /**
     * @param ProfileRequest $request
     * @return array
     */
    public function getRequestData($request)
    {
        $data = $request->except('clear', 'address', 'profile_image', 'password_confirmation', 'channel', 'two_factor_auth_enabled');

        $data['notification_preferences'] = $request->get('notification_preferences', $this->model->notification_preferences ?? []);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        return $data;
    }

    /**
     * @param ProfileRequest $request
     * @return array
     */
    public function setTwoFactorAuthData(ProfileRequest $request)
    {
        $data = $request->all();

        if (!TwoFactorAuth::isRegistered($this->model)) {
            $this->model->setAuthPhoneInformation($data['phone_country_code'] ?? '', $data['phone_number'] ?? '');
            $twoFactorOptions = TwoFactorAuth::register($this->model, $request);
        } else {
            $twoFactorOptions = $this->model->getTwoFactorAuthProviderOptions();
        }

        $twoFactorOptions['channel'] = $request->get('channel') ?? $twoFactorOptions['channel'];
        $twoFactorOptions['enabled'] = $request->get('two_factor_auth_enabled') ? true : false;
        $this->model->setTwoFactorAuthProviderOptions($twoFactorOptions);
        $this->model->save();

        return $data;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function updateProfile($data)
    {
        $this->model->update($data);

        return $this->model;
    }

    /**
     * @param ProfileRequest $request
     * @return mixed
     */
    public function handleUserProfileImage(ProfileRequest $request)
    {
        if (isset($request->profile_image)) {
            $this->model->clearMediaCollection('user-picture');

            $this->model->addMediaFromBase64($request->profile_image)->usingFileName('profile.png')
                ->withCustomProperties(['root' => 'user_' . $this->model->hashed_id])
                ->toMediaCollection('user-picture');
        }

        return $this->model;
    }

    public function setPassword($password)
    {
        $this->model->update(['password' => $password]);

        return $this->model;
    }
}