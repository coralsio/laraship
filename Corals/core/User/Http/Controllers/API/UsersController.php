<?php

namespace Corals\User\Http\Controllers\API;

use Corals\Foundation\DataTables\CoralsScope;
use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\User\DataTables\UsersDataTable;
use Corals\User\Http\Requests\UserRequest;
use Corals\User\Models\User;
use Corals\User\Services\UserService;
use Corals\User\Transformers\API\UserPresenter;

class UsersController extends APIBaseController
{
    protected $userService;

    /**
     * UsersController constructor.
     * @param UserService $userService
     * @throws \Exception
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->userService->setPresenter(new UserPresenter());

        parent::__construct();
    }

    /**
     * @param UserRequest $request
     * @param UsersDataTable $dataTable
     * @return mixed
     * @throws \Exception
     */
    public function index(UserRequest $request, UsersDataTable $dataTable)
    {
        $users = $dataTable->query(new User());

        return $this->userService->index($users, $dataTable);
    }

    /**
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(UserRequest $request)
    {
        try {
            $user = $this->userService->store($request, User::class);

            return apiResponse($this->userService->getModelDetails(), trans('Corals::messages.success.created', ['item' => $user->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }


    /**
     * @param UserRequest $request
     * @param User $user
     * @return $this
     */
    public function show(UserRequest $request, User $user)
    {
        try {
            return apiResponse($this->userService->getModelDetails($user));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param UserRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UserRequest $request, User $user)
    {
        try {
            $user = $this->userService->update($request, $user);

            return apiResponse($this->userService->getModelDetails(), trans('Corals::messages.success.updated', ['item' => $user->name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param UserRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(UserRequest $request, User $user)
    {
        try {
            $name = $user->name;

            $this->userService->destroy($request, $user);

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $name]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}