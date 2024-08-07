<?php

namespace Corals\User\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Corals\User\Facades\CoralsAuthentication;
use Corals\User\Models\User;
use Corals\User\Traits\UserLoginTrait;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use UserLoginTrait;
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        attemptLogin as baseAttemptLogin;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|mixed
     * @throws \Exception
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     * @throws ValidationException
     */
    protected function attemptLogin(Request $request)
    {
        if ($this->guard()->validate($this->credentials($request))) {
            $user = $this->guard()->getLastAttempted();

            if ($user->confirmed || !(\Settings::get('confirm_user_registration_email', false))) {
                return $this->baseAttemptLogin($request);
            }

            throw ValidationException::withMessages([
                'confirmation' => [
                    trans('User::messages.confirmation.not_confirmed')
                ]
            ]);
        }

        return false;
    }


    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    protected function authenticated(Request $request, User $user)
    {
        return $this->doLogin($user);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
       CoralsAuthentication::logout();

        return apiResponse([], trans('User::messages.auth.logout_success'));
    }

    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $model = $this->guard()->getProvider()->createModel();

        $user = $model->where('email', $request->get('email'))->firstOrFail();

        $randomPassword = \Str::random(6);

        $user->update([
            'password' => $randomPassword
        ]);

        event('notifications.login.reset_password', ['user' => $user, 'password' => $randomPassword]);

        return apiResponse([], trans('User::messages.auth.reset_password'));
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }
}
