<?php

namespace Corals\User\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\User\Facades\Users;
use Corals\User\Facades\Roles;
use Corals\User\Models\User;
use Corals\User\Transformers\UserTransformer;
use Yajra\DataTables\EloquentDataTable;

class UsersDataTable extends BaseDataTable
{
    protected $usesQueryBuilderFilters = true;

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('user.models.user.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new UserTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(User $model)
    {
        $model = $model->with('roles', 'groups')->select('users.*');

        return $model;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            'id' => ['visible' => false],
            'picture_thumb' => [
                'width' => '35px',
                'exportable' => false,
                'title' => trans('User::attributes.user.picture_thumb'),
                'orderable' => false,
                'searchable' => false
            ],
            'name' => ['title' => trans('User::attributes.user.name')],
            'last_name' => ['title' => trans('User::attributes.user.last_name')],
            'email' => ['title' => trans('User::attributes.user.email')],
            'status' => ['title' => trans('Corals::attributes.status')],
            'groups' => ['title' => trans('User::module.group.title'), 'orderable' => false, 'searchable' => false],
            'roles' => [
                'name' => 'roles.name',
                'title' => trans('User::attributes.user.roles'),
                'orderable' => false,
                'searchable' => false
            ],
        ];

        if ((\Settings::get('confirm_user_registration_email', false))) {
            $columns = array_merge($columns, [
                'confirmed' => ['title' => trans('User::attributes.user.confirmed'), 'name' => 'confirmed_at'],
            ]);
        }

        $columns = array_merge($columns, [
            'created_at' => ['title' => trans('Corals::attributes.created_at')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ]);

        return $columns;
    }

    public function getFilters()
    {
        return [
            'name' => [
                'title' => trans('User::attributes.user.name'),
                'class' => 'col-md-2',
                'type' => 'text',
                'condition' => 'like',
                'active' => true
            ],
            'last_name' => [
                'title' => trans('User::attributes.user.last_name'),
                'class' => 'col-md-2',
                'type' => 'text',
                'condition' => 'like',
                'active' => true
            ],
            'groups.id' => [
                'title' => trans('User::module.group.title'),
                'class' => 'col-md-2',
                'type' => 'select',
                'options' => Users::getGroupsList(),
                'active' => true
            ],
            'roles.id' => [
                'title' => trans('User::attributes.user.roles'),
                'class' => 'col-md-2',
                'type' => 'select2',
                'options' => Roles::getRolesList(),
                'active' => true
            ],
            'created_at' => [
                'title' => trans('Corals::attributes.created_at'),
                'class' => 'col-md-4',
                'type' => 'date_range',
                'active' => true
            ],
        ];
    }


    protected function getBulkActions()
    {
        if (request()->has("deleted")) {
            return [
                'hardDelete' => [
                    'title' => trans('Corals::labels.hardDelete'),
                    'permission' => 'User::user.hardDelete',
                    'confirmation' => trans('Corals::labels.confirmation.title')
                ],
            ];
        } else {
            return [
                'delete' => [
                    'title' => trans('Corals::labels.delete'),
                    'permission' => 'User::user.delete',
                    'confirmation' => trans('Corals::labels.confirmation.title')
                ],
            ];
        }
    }

    protected function getOptions()
    {
        $url = url(config('user.models.user.resource_url'));
        return ['resource_url' => $url];
    }


}
