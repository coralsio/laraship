<?php

namespace Corals\User\Http\Controllers\API\Auth;

use Corals\User\Http\Controllers\Auth\RegisterController as WebRegisterController;
use Corals\User\Traits\UserLoginTrait;
use Illuminate\Http\Request;

class RegisterController extends WebRegisterController
{

    use UserLoginTrait;

    /**
     * @param Request $request
     * @param null $roleName
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     */
    public function register(Request $request, $roleName = null)
    {
        if (!\Settings::get('registration_enabled')) {
            return abort(403);
        }

        $user = $this->createUserObject($request);

        if (\Settings::get('confirm_user_registration_email', false)) {
            $this->sendConfirmationToUser($user);
            return apiResponse([], trans('User::messages.confirmation.confirm_email'));
        } else {
            return $this->doLogin($user, trans('User::messages.auth.registered_successfully'));
        }
    }


    /**
     * @param $confirmation_code
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function confirm($confirmation_code)
    {
        $model = $this->guard()->getProvider()->createModel();
        $user = $model->where('confirmation_code', $confirmation_code)->firstOrFail();

        $user->confirmation_code = null;
        $user->confirmed_at = now();

        $user->save();

        return $this->doLogin($user, trans('User::messages.confirmation.confirmation_successful'));
    }
}
