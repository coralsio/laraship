<?php

namespace Corals\User\Http\Controllers;

use Corals\Foundation\Http\Controllers\AuthBaseController;
use Corals\User\Facades\TwoFactorAuth;
use Corals\User\Http\Requests\ProfileRequest;
use Corals\User\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends AuthBaseController
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;

        $this->resource_url = 'profile';

        $this->title = 'User::module.profile.title';
        $this->title_singular = 'User::module.profile.title_singular';

        parent::__construct();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $active_tab = 'profile';
        $active_tab = \Filters::do_filter('active_profile_tab', $active_tab, user());
        return view('auth.profile')->with(compact('active_tab'));
    }

    /**
     * @param ProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(ProfileRequest $request)
    {
        try {
            $this->profileService->setModel(user());

            $data = $this->profileService->getRequestData($request);
            $user = $this->profileService->updateProfile($data);


            if (TwoFactorAuth::isActive()) {
                $this->profileService->setTwoFactorAuthData($request);
            }

            $this->profileService->handleUserProfileImage($request);

            flash(trans('Corals::messages.success.updated', ['item' => user()->name]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, 'Profile', 'update');
        }

        return redirectTo('profile');
    }
}