<?php

namespace Corals\User\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\User\DataTables\RolesDataTable;
use Corals\User\Http\Requests\RoleRequest;
use Corals\User\Models\Role;
use Illuminate\Http\Request;

class RolesController extends BaseController
{

    public function __construct()
    {
        $this->resource_url = 'roles';

        $this->resource_model = new Role();

        $this->title = 'User::module.role.title';
        $this->title_singular = 'User::module.role.title_singular';

        parent::__construct();
    }

    /**
     * @param RoleRequest $request
     * @param RolesDataTable $dataTable
     * @return mixed
     */
    public function index(RoleRequest $request, RolesDataTable $dataTable)
    {
        return $dataTable->render('User::roles.index');
    }

    /**
     * @param RoleRequest $request
     * @return $this
     */
    public function create(RoleRequest $request)
    {
        $role = new Role();

        $this->setViewSharedData(['title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])]);

        return view('User::roles.create_edit')->with(compact('role'));
    }

    /**
     * @param RoleRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(RoleRequest $request)
    {
        try {
            $data = $request->all();

            $permissions = $data['permissions'] ?? [];

            unset($data['permissions']);

            $role = Role::create($data);

            $role->permissions()->sync($permissions);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Role::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param RoleRequest $request
     * @param Role $role
     * @return $this
     */
    public function edit(RoleRequest $request, Role $role)
    {
        $this->setViewSharedData(['title_singular' => trans('Corals::labels.update_title', ['title' => $role->label])]);

        return view('User::roles.create_edit')->with(compact('role'));
    }

    /**
     * @param RoleRequest $request
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(RoleRequest $request, Role $role)
    {
        try {
            $data = $request->except('name');

            $permissions = $data['permissions'] ?? [];

            unset($data['permissions']);

            $role->update($data);
            $role->touch();

            $role->permissions()->sync($permissions);

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Role::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param RoleRequest $request
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(RoleRequest $request, Role $role)
    {
        try {
            $super_user_role = \Settings::get('super_user_role_id', 1);

            if ($role->id == $super_user_role) {
                throw new \Exception(trans('User::exceptions.invalid_destroy_roles'));
            }

            $role->delete();

            $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])];
        } catch (\Exception $exception) {
            log_exception($exception, Role::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param RoleRequest $request
     * @param Role $role
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getBulkUpdateModal(RoleRequest $request, Role $role)
    {
        $roles = Role::where('name', '<>', 'superuser')->get();

        return view('User::roles.bulk_update')->with(compact('roles'));
    }

    /**
     * @param Request $request
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitBulkUpdate(Request $request, Role $role)
    {
        try {
            $permissionsHasRole = $request->all();
            unset($permissionsHasRole['_token']);

            foreach ($permissionsHasRole as $roleName => $permissions) {
                $role = Role::findByName($roleName);
                $role->permissions()->sync($permissions);
            }

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.updated', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
            log_exception($exception, Role::class, 'update');
        }

        return response()->json($message);
    }
}