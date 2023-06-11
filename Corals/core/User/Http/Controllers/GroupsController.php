<?php

namespace Corals\User\Http\Controllers;


use Corals\Foundation\Http\Controllers\BaseController;
use Corals\User\DataTables\GroupsDataTable;
use Corals\User\Http\Requests\GroupRequest;
use Corals\User\Models\Group;
use Corals\User\Services\GroupService;
use Illuminate\Http\Request;

class GroupsController extends BaseController
{
    protected $groupService;

    /**
     * GroupsController constructor.
     * @param GroupService $groupService
     * @throws \Exception
     */
    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
        $this->resource_url = config('user.models.group.resource_url');

        $this->resource_model = new Group();

        $this->title = 'User::module.group.title';
        $this->title_singular = 'User::module.group.title_singular';

        parent::__construct();
    }

    /**
     * @param GroupRequest $request
     * @param GroupsDataTable $dataTable
     * @return mixed
     * @throws \Exception
     */
    public function index(GroupRequest $request, GroupsDataTable $dataTable)
    {
        return $dataTable->render('User::groups.index');
    }

    /**
     * @param GroupRequest $request
     * @return $this
     */
    public function create(GroupRequest $request)
    {
        $group = new Group();

        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])
        ]);

        return view('User::groups.create_edit')->with(compact('group'));
    }


    /**
     * @param GroupRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(GroupRequest $request)
    {
        try {
            $group = $this->groupService->store($request, Group::class);

            flash(trans('Corals::messages.success.created', ['item' => $group->name]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Group::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param GroupRequest $request
     * @param Group $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(GroupRequest $request, Group $group)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.show_title', ['title' => $group->name])
        ]);

        return view('User::groups.show')->with(compact('group'));
    }

    /**
     * @param GroupRequest $request
     * @param Group $group
     * @return $this
     */
    public function edit(GroupRequest $request, Group $group)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.update_title', ['title' => $group->name])
        ]);

        return view('User::groups.create_edit')->with(compact('group'));
    }

    /**
     * @param GroupRequest $request
     * @param Group $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(GroupRequest $request, Group $group)
    {
        try {
            $this->groupService->update($request, $group);

            flash(trans('Corals::messages.success.updated', ['item' => $group->name]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Group::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param GroupRequest $request
     * @param Group $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(GroupRequest $request, Group $group)
    {
        try {
            $name = $group->name;

            $this->groupService->destroy($request, $group);

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.deleted', ['item' => $name])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, Group::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param Request $request
     * @param Group $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore(Request $request, Group $group)
    {
        try {
            $name = $group->name;

            $this->groupService->restore($request, $group);

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.restore', ['item' => $name])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, Group::class, 'restore');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
            $code = 400;
        }

        return response()->json($message, $code ?? 200);
    }

    /**
     * @param GroupRequest $request
     * @param Group $group
     * @return \Illuminate\Http\JsonResponse
     */
    public function hardDelete(GroupRequest $request, Group $group)
    {
        try {
            $name = $group->name;

            $this->groupService->hardDelete($request, $group);

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.deleted', ['item' => $name])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, Group::class, 'hardDelete');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}
