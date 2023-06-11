<?php

namespace Corals\User\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\User\Models\Group;
use Corals\User\Transformers\GroupTransformer;
use Yajra\DataTables\EloquentDataTable;

class GroupsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('user.models.group.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new GroupTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Group $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Group $model)
    {
        return $model->newQuery();
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
            'name' => ['title' => trans('User::attributes.group.name')],

            'created_at' => ['title' => trans('Corals::attributes.created_at')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }

    public function getFilters()
    {
        return [
            'name' => ['title' => trans('User::attributes.group.name'), 'class' => 'col-md-4', 'type' => 'text', 'condition' => 'like', 'active' => true],
            'created_at' => ['title' => trans('Corals::attributes.created_at'), 'class' => 'col-md-6', 'type' => 'date_range', 'active' => true],
        ];
    }
}
