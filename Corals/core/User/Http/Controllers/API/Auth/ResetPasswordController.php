<?php

namespace Corals\User\Http\Controllers\API\Auth;

use Carbon\Carbon;
use Corals\User\Facades\CoralsAuthentication;
use Corals\User\Http\Controllers\Auth\ResetPasswordController as WebResetPasswordController;
use Corals\User\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;

class ResetPasswordController extends WebResetPasswordController
{
    /**
     * Reset the given user's password.
     *
     * @param \Illuminate\Contracts\Auth\CanResetPassword $user
     * @param string $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = $password;

        $user->setRememberToken(\Str::random(60));

        $user->forgetProperty('force_reset');

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);

    }

    /**
     * Get the response for a successful password reset.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        /**
         * @var $user User
         */
        $user = $this->guard()->user();

        $user->setPresenter(new \Corals\User\Transformers\API\UserPresenter());

        $userDetails = $user->presenter();

        CoralsAuthentication::login(
            user: $user,
            userDetails: $userDetails
        );

        return apiResponse($userDetails);
    }
}
