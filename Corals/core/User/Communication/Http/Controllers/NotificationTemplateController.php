<?php

namespace Corals\User\Communication\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Foundation\Http\Requests\BulkRequest;
use Corals\User\Communication\DataTables\NotificationTemplatesDataTable;
use Corals\User\Communication\Facades\CoralsNotification;
use Corals\User\Communication\Http\Requests\NotificationTemplateRequest;
use Corals\User\Communication\Models\NotificationTemplate;

class NotificationTemplateController extends BaseController
{

    public function __construct()
    {
        $this->resource_url = config('notification.models.notification_template.resource_url');

        $this->resource_model = new NotificationTemplate();

        $this->title = 'Notification::module.notification_template.title';
        $this->title_singular = 'Notification::module.notification_template.title_singular';

        parent::__construct();
    }

    /**
     * @param NotificationTemplateRequest $request
     * @param NotificationTemplatesDataTable $dataTable
     * @return mixed
     */
    public function index(NotificationTemplateRequest $request, NotificationTemplatesDataTable $dataTable)
    {
        CoralsNotification::insertNewEventsToDatabase();

        $showCreateButton = false;

        return $dataTable->render('Notification::notification_template.index', compact('showCreateButton'));
    }

    public function show(NotificationTemplateRequest $request, NotificationTemplate $notification_template)
    {
        $subject = $notification_template->title;

        $body = $notification_template->body['mail'] ?? '';

        return view('Notification::mail.general_email_template')->with(compact('subject', 'body'));
    }

    /**
     * @param NotificationTemplateRequest $request
     * @param NotificationTemplate $notification_template
     * @return $this
     */
    public function edit(NotificationTemplateRequest $request, NotificationTemplate $notification_template)
    {
        $this->setViewSharedData(['title_singular' => trans('Corals::labels.update_title', ['title' => $notification_template->friendly_name])]);

        $notificationParametersDescription = CoralsNotification::getNotificationParametersDescription($notification_template);

        $body = $notification_template->body;

        return view('Notification::notification_template.create_edit')->with(compact('notification_template', 'notificationParametersDescription', 'body'));
    }

    /**
     * @param NotificationTemplateRequest $request
     * @param NotificationTemplate $notification_template
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(NotificationTemplateRequest $request, NotificationTemplate $notification_template)
    {
        try {
            $data = $request->except('role_ids');

            $data['extras'] = $request->get('extras', []);

            $data['via'] = $request->get('via', []);

            $data['extras']['custom'] = $data['extras']['custom'] ?? [];

            $rolesIds = $request->get('role_ids', []);

            $notification_template->roles()->sync($rolesIds);

            $notification_template->update($data);

            flash(trans('Corals::messages.success.updated', ['item' => 'NotificationTemplate']))->success();
        } catch (\Exception $exception) {
            log_exception($exception, NotificationTemplate::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param BulkRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkAction(BulkRequest $request)
    {
        try {

            $action = $request->input('action');
            $selection = json_decode($request->input('selection'), true);
            $successCount = 0;
            $failedCount = 0;

            switch ($action) {

                case 'toggleStatus' :
                    foreach ($selection as $selection_id) {
                        $template = NotificationTemplate::findByHash($selection_id);

                        if (user()->can('Notification::notification_template.update')) {
                            $template->update([
                                'status' => $template->status === 'active' ? 'inactive' : 'active'
                            ]);

                            $successCount++;
                        } else {
                            $failedCount++;
                        }
                    }
                    break;
            }

            if (!$successCount && !$failedCount) {
                $message = ['level' => 'info', 'message' => "no record been selected"];
            } else {
                $message = ['level' => 'success', 'message' => trans('Corals::messages.success.updated', ['item' => 'Notification Templates'])];
            }

        } catch (\Exception $exception) {
            log_exception($exception, NotificationTemplate::class, 'bulkAction');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}
