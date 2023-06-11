<?php

namespace Corals\User\Http\Controllers;


use Corals\Foundation\Http\Controllers\BaseController;
use Corals\User\Http\Requests\UserAddressRequest;
use Corals\User\Models\User;
use Corals\User\Services\UserAddressService;
use Illuminate\Http\Request;

class UserAddressesController extends BaseController
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
     * @throws \Throwable
     */
    public function store(UserAddressRequest $request, User $user)
    {
        try {
            $address = $request->get('address');

            $this->addressService->setModel($user);

            $this->addressService->storeAddress($address);

            $addressListForm = view('Settings::addresses.address_list_form', [
                'url' => url('users/' . $user->hashed_id . '/address'), 'method' => 'POST',
                'model' => $user,
                'addressDiv' => '#profile_addresses'
            ])->render();

            $message = [
                'level' => 'success', 'message' => trans('Corals::messages.success.saved', ['item' => trans('User::module.address.title')]),
                'action' => 'refresh_address',
                'address_list' => $addressListForm
            ];
        } catch (\Exception $exception) {
            log_exception($exception, User::class, 'storeAddress');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param Request $request
     * @param User $user
     * @param $type
     * @return \Illuminate\Http\JsonResponse|string
     * @throws \Throwable
     */
    public function edit(Request $request, User $user, $type)
    {
        try {
            $userAddress = $user->address ?? [];

            $address = $userAddress[$type];
            $address['type'] = $type;

            $addressListForm = view('Settings::addresses.address_list_form', [
                'url' => url('users/' . $user->hashed_id . '/address'), 'method' => 'POST',
                'model' => $user,
                'object' => $address,
                'addressDiv' => '#profile_addresses'
            ])->render();

            return $addressListForm;

        } catch (\Exception $exception) {
            log_exception($exception, User::class, 'storeAddress');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
            return response()->json($message);
        }
    }

    /**
     * @param Request $request
     * @param User $user
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function destroy(Request $request, User $user, $type)
    {
        try {
            $this->addressService->setModel($user);

            $this->addressService->destroyAddress($type);

            $addressListForm = view('Settings::addresses.address_list_form', [
                'url' => url('users/' . $user->hashed_id . '/address'), 'method' => 'POST',
                'model' => $user,
                'addressDiv' => '#profile_addresses'
            ])->render();

            $message = [
                'level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => trans('User::module.address.title')]),
                'action' => 'refresh_address',
                'address_list' => $addressListForm
            ];
        } catch (\Exception $exception) {
            log_exception($exception, User::class, 'storeAddress');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}