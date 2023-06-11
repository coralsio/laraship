<?php

namespace Corals\User\Communication\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\User\Communication\Models\Notification;
use Corals\User\Communication\Transformers\NotificationTransformer;
use Corals\User\Models\User;
use Yajra\DataTables\EloquentDataTable;

class NotificationDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('notification.models.notification.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new NotificationTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Notification $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Notification $model)
    {
        $user = user();
        return $model->newQuery()
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', getMorphAlias(User::class))
            ->orderBy('created_at', 'desc');
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
            'title' => ['title' => trans('Notification::attributes.notification_template.title'), 'orderable' => false, 'searchable' => false],
            'read_at' => ['title' => trans('Notification::attributes.notification_template.read_at')],
            'created_at' => ['title' => trans('Corals::attributes.created_at')]
        ];
    }

    /**
     * @return array
     */
    protected function getBulkActions()
    {
        return [
            'MarkAsRead' => ['title' => trans('Notification::labels.mark_as_read'), 'permission' => 'Notification::my_notification.update', 'confirmation' => 'mark as read?'],
        ];
}

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            'resource_url' => url('notifications')
        ];
    }}
