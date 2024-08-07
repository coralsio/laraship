<?php

namespace Corals\User\Http\Controllers\Auth;

use App\Exceptions\Handler;
use Corals\Foundation\Facades\Actions;
use Corals\Foundation\Http\Controllers\AuthBaseController;
use Corals\Settings\Facades\Settings;
use Corals\User\Facades\CoralsAuthentication;
use Corals\User\Facades\TwoFactorAuth;
use Corals\User\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends AuthBaseController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->corals_middleware = ['guest'];
        parent::__construct();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $is_two_factor_auth_enabled = \TwoFactorAuth::isActive();

        $rules = [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'terms' => 'required|in:1',

        ];

        if ($is_two_factor_auth_enabled) {
            $rules = array_merge($rules, TwoFactorAuth::registrationValidation(request()->all()));
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration
     * @param array $data
     * @param null $roleName
     * @return mixed
     * @throws \Exception
     */
    protected function create(array $data, $roleName = null)
    {
        $user = User::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone_country_code' => $data['phone_country_code'] ?? '',
            'phone_number' => $data['phone_number'] ?? ''
        ]);

        $this->assignDefaultRoles($user, $roleName);

        try {
            TwoFactorAuth::register($user, request());

            return $user;
        } catch (\Exception $exception) {
            $user->forceDelete();

            app(Handler::class)->report($exception);

            throw new \Exception('Unable To Register User');
        }
    }

    protected function createUserObject($request, $roleName = null)
    {
        $data = $request->all();

        $this->validator($data)->validate();

        $user = $this->create($data, $roleName);

        event(new Registered($user));

        return $user;
    }

    /**
     * Handle a registration request for the application.
     * @param Request $request
     * @param null $roleName
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    public function register(Request $request, $roleName = null)
    {
        if (!Settings::get('registration_enabled')) {
            abort(403);
        }

        try {
            Actions::do_action('pre_registration_submit', $request);
        } catch (\Exception $exception) {
            flash($exception->getMessage())->error();
            return redirect($request->fullUrl());
        }

        $user = $this->createUserObject($request, $roleName);
        Actions::do_action('user_registered', $user, $request);

        if (TwoFactorAuth::isEnabled($user)) {
            $request->session()->put('authy:auth:id', $user->id);
            return redirectTo(url('auth/token'));
        }

        if (Settings::get('confirm_user_registration_email', false)) {
            $this->sendConfirmationToUser($user);

            flash(trans('User::messages.confirmation.confirm_email'), 'success');

            return redirectTo('login');
        } else {
            $this->guard()->login($user);

            $role = $user->roles()->first();
            if (!empty($role->dashboard_theme)) {
                session()->put('dashboard_theme', $role->dashboard_theme);
            }

            if ($request->input('no_redirect')) {
                $result = ['status' => 'success', 'user' => $user];
                return response()->json($result);
            } else {
                return $this->registered($request, $user)
                    ?: redirectTo($this->redirectPath());
            }
        }
    }


    /**
     * Send the confirmation code to a user.
     *
     * @param $user
     */
    protected function sendConfirmationToUser($user)
    {
        CoralsAuthentication::sendConfirmationToUser(
            user: $user
        );
    }


    /**
     * Confirm a user with a given confirmation code.
     *
     * @param $confirmation_code
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm($confirmation_code)
    {
        $model = $this->guard()->getProvider()->createModel();
        $user = $model->where('confirmation_code', $confirmation_code)->firstOrFail();

        $user->confirmation_code = null;
        $user->confirmed_at = now();

        $user->save();

        $this->guard()->login($user);

        flash(trans('User::messages.confirmation.confirmation_successful'), 'success');

        return $this->confirmed($user)
            ?: redirect($this->redirectPath());
    }

    /**
     * The users email address has been confirmed.
     *
     * @param mixed $user
     * @return mixed
     */
    protected function confirmed($user)
    {
        //
    }


    /**
     * Resend a confirmation code to a user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resendConfirmation(Request $request)
    {
        $model = $this->guard()->getProvider()->createModel();

        $user = $model->findOrFail($request->session()->pull('confirmation_user_id'));

        $this->sendConfirmationToUser($user);

        flash(trans('User::messages.confirmation.confirmation_resent'), 'success');

        return redirect(route('login'));
    }

    public function showRegistrationForm(Request $request, $roleName = null)
    {
        if (!Settings::get('registration_enabled')) {
            return redirectTo('login');
        }

        try {
            Actions::do_action('pre_registration_submit', $request);
        } catch (\Exception $exception) {
            report($exception);
            flash($exception->getMessage())->error();
            return redirect($request->fullUrlWithoutQuery([]));
        }

        $available_registration_roles = Settings::get('available_registration_roles', []);

        $view = 'auth.register';

        if (!empty($roleName)) {
            if (in_array($roleName, array_keys($available_registration_roles))) {
                $roleView = 'auth.register.' . $roleName;

                if (view()->exists($roleView)) {
                    $view = $roleView;
                }
            } else {
                abort(404);
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
}
