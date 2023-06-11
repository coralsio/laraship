<?php

namespace Corals\User\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\User\Models\Role;
use Corals\User\Transformers\RoleTransformer;
use Yajra\DataTables\EloquentDataTable;

class RolesDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('user.models.role.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new RoleTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Role $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Role $model)
    {
        return $model->withCount('users');
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id' => ['visible' => false],
            'name' => ['title' => trans('User::attributes.role.name')],
            'label' => ['title' => trans('User::attributes.role.label')],
            'users_count' => ['title' => trans('User::attributes.role.users_count'), 'searchable' => false],
            'subscription_required' => ['title' => trans('User::attributes.role.subscription_required')],
            'created_at' => ['title' => trans('Corals::attributes.created_at')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }
}
