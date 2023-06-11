<?php

namespace Corals\Settings\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Settings\Models\Setting;
use Corals\Settings\Transformers\SettingTransformer;
use Yajra\DataTables\EloquentDataTable;

class SettingsDataTable extends BaseDataTable
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

        return $dataTable->setTransformer(new SettingTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Setting $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Setting $model)
    {
        return $model->visible()->newQuery();
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
            'label' => ['title' =>trans('Settings::attributes.setting.label')],
            'value' => ['width' => '60%', 'title' => trans('Settings::attributes.setting.value')],

        ];
    }
}
