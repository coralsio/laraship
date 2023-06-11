<?php

namespace Corals\User\Http\Controllers\Auth;

use Corals\Foundation\Http\Controllers\AuthBaseController;
use Corals\User\Facades\TwoFactorAuth;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class LoginController extends AuthBaseController
{
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
        credentials as baseCredentials;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->corals_middleware = ['guest'];
        $this->corals_middleware_except = ['logout'];
        parent::__construct();
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param \Illuminate\Http\Request $request
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

            session([
                'confirmation_user_id' => $user->getKey()
            ]);

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
     * @param Authenticatable $user
     * @return Application|JsonResponse|RedirectResponse|Response|Redirector
     */
    protected function authenticated(Request $request, Authenticatable $user)
    {
        if (TwoFactorAuth::isEnabled($user)) {
            return $this->logoutAndRedirectToTokenScreen($request, $user);
        }

        $role = $user->roles()->first();

        if (!$role || $role->disable_login) {
            $this->guard()->logout();

            flash(trans('User::messages.auth.role_cannot_login'), 'warning');

            return redirect('login');
        }

        if ($user->getProperty('force_reset')) {
            $brokerUser = $this->broker()->getUser(['email' => $user->email]);

            $tokens = $this->broker()->getRepository();

            $token = $tokens->create($brokerUser);

            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $user->email,
            ], false));

            Auth::logout();

            return redirect()->to($url);
        }

        if (!empty($role->dashboard_theme)) {
            session()->put('dashboard_theme', $role->dashboard_theme);
        }


        $request = request();

        if ($request->wantsJson()) {
            $result = ['status' => 'success', 'user' => $user, 'csrf_token' => csrf_token()];

            return response()->json($result);
        } else {
            return redirect()->intended($this->redirectPath());
        }
    }

    /**
     * Generate a redirect response to the two-factor token screen.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     *
     * @return Response
     */
    protected function logoutAndRedirectToTokenScreen(Request $request, Authenticatable $user)
    {
        $this->guard()->logout();

        $request->session()->put('authy:auth:id', $user->id);

        return redirect(url('auth/token'));
    }


    public function showLoginForm($roleName = null)
    {
        $view = 'auth.login';

        if (!empty($roleName)) {
            $roleView = 'auth.login.' . $roleName;
            if (view()->exists($roleView)) {
                $view = $roleView;
            }
        }

        return view($view);
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        return Parent::redirectPath();
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

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $credentials = $this->baseCredentials($request);
        return array_merge($credentials, ['status' => 'active']);
    }
}
