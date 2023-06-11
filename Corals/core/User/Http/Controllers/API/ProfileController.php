<?php

namespace Corals\User\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\User\Http\Requests\ProfileRequest;
use Corals\User\Services\ProfileService;
use Corals\User\Transformers\API\UserPresenter;
use Illuminate\Http\Request;

class ProfileController extends APIBaseController
{
    protected $profileService;

    /**
     * ProfileController constructor.
     * @param ProfileService $profileService
     * @throws \Exception
     */
    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
        $this->profileService->setPresenter(new UserPresenter());

        parent::__construct();
    }

    public function getProfileDetails(Request $request)
    {
        try {
            return apiResponse($this->profileService->getModelDetails(user()));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    public function update(ProfileRequest $request)
    {
        try {
            $this->profileService->setModel(user());

            $data = $this->profileService->getRequestData($request);

            $user = $this->profileService->updateProfile($data);

            $this->profileService->handleUserProfileImage($request);

            return apiResponse($this->profileService->getModelDetails(), trans('Corals::messages.success.updated', ['item' => $user->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    public function setPassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|max:191|min:6',
        ]);

        try {
            $this->profileService->setModel(user());

            $this->profileService->setPassword($request->get('password'));

            return apiResponse([], trans('Corals::messages.success.updated', ['item' => trans('User::attributes.user.password')]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
