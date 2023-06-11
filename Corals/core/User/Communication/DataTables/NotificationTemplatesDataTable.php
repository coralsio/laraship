<?php

namespace Corals\User\Communication\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\User\Communication\Models\NotificationTemplate;
use Corals\User\Communication\Transformers\NotificationTemplateTransformer;
use Yajra\DataTables\EloquentDataTable;

class NotificationTemplatesDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('notification.models.notification_template.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new NotificationTemplateTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param NotificationTemplate $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(NotificationTemplate $model)
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
            'friendly_name' => ['title' => trans('Notification::attributes.notification_template.friendly_name')],
            'name' => ['title' => trans('Notification::attributes.notification_template.name')],
            'title' => ['title' => trans('Notification::attributes.notification_template.title')],
            'status' => ['title' =>trans('Corals::attributes.status')],
            'created_at' => ['title' => trans('Corals::attributes.created_at')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }

    public function getFilters()
    {
        return [
            'name' => ['title' => trans('Notification::attributes.notification_template.name'), 'class' => 'col-md-2', 'type' => 'text', 'condition' => 'like', 'active' => true],
            'title' => ['title' => trans('Notification::attributes.notification_template.title'), 'class' => 'col-md-2', 'type' => 'text', 'condition' => 'like', 'active' => true],
            'status' => ['title' => trans('Corals::attributes.status'), 'class' => 'col-md-2', 'type' => 'select2', 'options' => trans('Corals::attributes.status_options'), 'active' => true],
        ];
    }

    /**
     * @return \string[][]
     */
    protected function getBulkActions()
    {
        return [
            'toggleStatus' => [
                'title' => trans('Notification::labels.notification_template.toggle_status'),
                'permission' => 'Notification::notification_template.update',
                'confirmation' => trans('Notification::labels.notification_template.toggle_status_confirmation')
            ]
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        $url = url(config('notification.models.notification_template.resource_url'));

        return [
            'resource_url' => $url
        ];
    }
}
