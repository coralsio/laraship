<?php

namespace Corals\User\Http\Controllers\Auth;

use App\Exceptions\Handler;
use Corals\Foundation\Http\Controllers\AuthBaseController;
use Corals\User\Facades\TwoFactorAuth;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;

class TwoFactorController extends AuthBaseController
{
    use RedirectsUsers;

    public function __construct()
    {
        $this->corals_middleware_except = ['showTokenForm', 'validateTokenForm'];
        parent::__construct();
    }

    protected function getUserFromSession($forgetAuthId = false)
    {
        $guard = config('auth.defaults.guard');
        $provider = config('auth.guards.' . $guard . '.provider');
        $model = config('auth.providers.' . $provider . '.model');

        $user = (new $model())->findOrFail(
            session('authy:auth:id')
        );

        if ($forgetAuthId) {
            session()->forget('authy:auth:id');
        }

        return $user;
    }

    /**
     * Show two-factor authentication page.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function showTokenForm()
    {
        if (session('authy:auth:id')) {
            try {
                $user = $this->getUserFromSession();

                TwoFactorAuth::sendToken($user);

                return view('auth.2fa.token')->with(compact('user'));
            } catch (\Exception $exception) {
                app(Handler::class)->report($exception);

                flash(trans('User::exceptions.invalid_send_token_user'), 'error');

                return redirect('login');
            }
        } else {
            return redirect(url('login'));
        }
    }

    /**
     * Verify the two-factor authentication token.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function validateTokenForm(Request $request)
    {
        $this->validate($request, ['token' => 'required']);

        if (!session('authy:auth:id')) {
            return redirect(url('login'));
        }

        $guard = config('auth.defaults.guard');

        $user = $this->getUserFromSession();

        if (TwoFactorAuth::tokenIsValid($user, $request->token)) {
            auth($guard)->login($user);

            return redirect()->intended($this->redirectPath());
        } else {
            flash(trans('User::exceptions.invalid_two_factor_user_token'), 'error');
            return redirect(url('auth/token'));
        }
    }

    /**
     * Enable/Disable two-factor authentication.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|null
     */
    public function setupTwoFactorAuth(Request $request)
    {
        $user = auth()->user();

        if (TwoFactorAuth::isEnabled($user)) {
            return $this->disableTwoFactorAuth($request, $user);
        } else {
            return $this->enableTwoFactorAuth($request, $user);
        }
    }

    /**
     * Enable two-factor authentication.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     *
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function enableTwoFactorAuth(Request $request, Authenticatable $user)
    {
        $input = $request->all();

        if (isset($input['phone_number'])) {
            $input['authy-cellphone'] = preg_replace('/[^0-9]/', '', $input['authy-cellphone']);
        }

        $validator = \Validator::make($input, [
            'country-code' => 'required|numeric|integer',
            'authy-cellphone' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect(url($this->redirectPath()))->withErrors($validator->errors());
        }

        $user->setAuthPhoneInformation(
            $input['country-code'], $input['authy-cellphone']
        );

        try {
            TwoFactorAuth::register($user, $request);

            $user->save();
        } catch (\Exception $e) {
            app(Handler::class)->report($e);

            //\FlashAlert::error('Error', 'The provided phone information is invalid.');
        }

        //\FlashAlert::success('Success', 'Two-factor authentication has been enabled!');

        return redirect(url($this->redirectPath()));
    }

    /**
     * Disable two-factor authentication.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function disableTwoFactorAuth(Request $request, Authenticatable $user)
    {
        try {
            TwoFactorAuth::delete($user);

            $user->save();
        } catch (\Exception $e) {
            app(Handler::class)->report($e);

            //\FlashAlert::error('Error', 'Unable to Delete User');
        }

        //\FlashAlert::success('Success', 'Two-factor authentication has been disabled!');

        return redirect(url($this->redirectPath()));
    }
}
