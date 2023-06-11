<?php

namespace Corals\Settings\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Settings\Models\CustomFieldSetting;
use Corals\Settings\Transformers\CustomFieldSettingTransformer;
use Yajra\DataTables\EloquentDataTable;

class CustomFieldsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('settings.models.setting.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new CustomFieldSettingTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param CustomFieldSetting $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CustomFieldSetting $model)
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
            'model' => ['title' => trans('Settings::attributes.custom_field.model')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')]
        ];
    }
}
