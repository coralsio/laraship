<?php

namespace Corals\User\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\User\Http\Requests\UserAddressRequest;
use Corals\User\Models\User;
use Corals\User\Services\UserAddressService;
use Illuminate\Http\Request;

class UserAddressesController extends APIBaseController
{
    protected $addressService;

    public function __construct(UserAddressService $addressService)
    {
        $this->addressService = $addressService;

        parent::__construct();
    }

    /**
     * @param UserAddressRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserAddressRequest $request, User $user)
    {
        try {
            $address = $request->get('address');

            $this->addressService->setModel($user);

            $this->addressService->storeAddress($address);

            return apiResponse([], trans('Corals::messages.success.saved', ['item' => trans('User::module.address.title')]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param UserAddressRequest $request
     * @param User $user
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(UserAddressRequest $request, User $user, $type)
    {
        try {
            $this->addressService->setModel($user);

            $this->addressService->destroyAddress($type);

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => trans('User::module.address.title')]));

        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}