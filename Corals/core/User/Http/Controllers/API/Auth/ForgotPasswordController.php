<?php

namespace Corals\User\Http\Controllers\API\Auth;

use Corals\User\Http\Controllers\Auth\ForgotPasswordController as WebCoralsForgotPasswordController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends WebCoralsForgotPasswordController
{
    /**
     * Send a reset link to the given user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $user = $this->broker()->getUser($request->only('email'));

        // If the user hasn't confirmed their email address,
        // we will throw a validation exception for this error.
        // A user can not request a password reset link if they are not confirmed.
        if ($user && !$user->confirmed && (\Settings::get('confirm_user_registration_email', false))) {
            throw ValidationException::withMessages([
                'confirmation' => [
                    trans('User::messages.confirmation.not_confirmed')
                ]
            ]);
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );


        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return apiResponse([], trans($response));
    }
}
