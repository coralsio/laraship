<?php

namespace Corals\User\Services;

use Corals\Foundation\Facades\Actions;
use Corals\Foundation\Services\BaseServiceClass;
use Corals\User\Facades\TwoFactorAuth;
use Corals\User\Http\Requests\UserRequest;

class UserService extends BaseServiceClass
{

    protected $excludedRequestParams = [
        'picture',
        'channel',
        'two_factor_auth_enabled',
        'password_confirmation',
        'roles',
        'clear',
        'confirmed',
        'send_login_details',
    ];

    public function getRequestData($request)
    {
        $data = $request->except($this->excludedRequestParams);

        if (
            ($request->has('confirmed') || !\Settings::get('confirm_user_registration_email', false))
            && is_null(optional($this->model)->confirmed_at)
        ) {
            $data['confirmed_at'] = now();
        } elseif (!$request->has('confirmed') && \Settings::get('confirm_user_registration_email', false)) {
            $data['confirmed_at'] = null;
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        return $data;
    }

    public function setTwoFactorAuthDetails(UserRequest $request)
    {
        if (!TwoFactorAuth::isActive()) {
            return;
        }

        if (!TwoFactorAuth::isRegistered($this->model)) {
            TwoFactorAuth::register($this->model, $request);
        }
    }


    public function handleUserPicture(UserRequest $request)
    {
        if ($request->has('clear') || $request->hasFile('picture')) {
            $this->model->clearMediaCollection('user-picture');
        }

        if ($request->hasFile('picture') && !$request->has('clear')) {
            $this->model->addMedia($request->file('picture'))
                ->withCustomProperties(['root' => 'user_' . $this->model->hashed_id])
                ->toMediaCollection('user-picture');
        }
    }

    public function handleUserGroups(UserRequest $request)
    {
        $this->model->groups()->sync($request->groups);
    }

    public function handleUserRoles(UserRequest $request)
    {
        $this->model->roles()->sync($request->roles);
    }

    public function store($request, $modelClass, $additionalData = [])
    {
        $data = array_merge($this->getRequestData($request), $additionalData);

        $user = $modelClass::query()->create($data);

        $this->model = $user;

        $this->postStoreUpdate($request, $additionalData);

        if ($request->has('send_login_details')) {
            event('notifications.user.send_login_details',
                ['user' => $user, 'password' => data_get($data, 'password')]);
        }

        Actions::do_action('user_just_created', $user);

        return $user;
    }

    public function update($request, $user, $additionalData = [])
    {
        $this->model = $user;

        $data = array_merge($this->getRequestData($request), $additionalData);

        $this->model->update($data);

        $this->postStoreUpdate($request, $additionalData);

        if ($user->status == 'inactive') {
            $user->tokens()->where('revoked', false)->eachById(function ($activeToken) {
                $activeToken->revoke();
            });
        }

        return $user;
    }

    public function postStoreUpdate($request, $additionalData)
    {
        $this->setTwoFactorAuthDetails($request);

        $this->handleUserPicture($request);

        $this->handleUserRoles($request);

        $this->handleUserGroups($request);
    }


    /**
     * @param $request
     * @param $user
     * @throws \Exception
     */
    public function destroy($request, $user)
    {
        if (user()->id == $user->id) {
            throw new \Exception(trans('User::exceptions.invalid_destroy_user'));
        }

        $user->delete();
    }
}
