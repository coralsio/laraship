<?php

namespace Corals\User\Communication\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Foundation\Http\Requests\BulkRequest;
use Corals\User\Communication\DataTables\NotificationDataTable;
use Corals\User\Communication\Http\Requests\NotificationRequest;
use Corals\User\Communication\Models\Notification;
use Mockery\Exception;

/**
 * Class NotificationController
 * @package Corals\User\Communication\Http\Controllers
 */
class NotificationController extends BaseController
{

    /**
     * NotificationController constructor.
     */
    public function __construct()
    {
        $this->resource_url = config('notification.models.notification.resource_url');

        $this->resource_model = new Notification();

        $this->title = 'Notification::module.notification.title';
        $this->title_singular = 'Notification::module.notification.title_singular';

        parent::__construct();
    }

    /**
     * @param NotificationRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(NotificationRequest $request, NotificationDataTable $dataTable)
    {
        $showCreateButton = false;
        return $dataTable->render('Notification::notification.index', compact('showCreateButton'));
    }

    /**
     * @param NotificationRequest $request
     * @param Notification $notification
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(NotificationRequest $request, Notification $notification)
    {
        $this->setViewSharedData(['title_singular' => trans('Corals::labels.show_title', ['title' => $this->title_singular])]);

        $notification->markAsRead();

        return view('Notification::notification.show')->with(compact('notification'));
    }

    /**
     * @param NotificationRequest $request
     * @param Notification $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function readAtToggle(NotificationRequest $request, Notification $notification)
    {
        try {
            $notification->toggleReadAt();
            $message = ['level' => 'success', 'message' => trans('Corals::messages.success.updated', ['item' => 'Notification'])];
        } catch (Exception $exception) {
            log_exception($exception, Notification::class, 'readAtToggle');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param BulkRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkAction(BulkRequest $request)
    {

        try {
            $action = $request->input('action');
            $selections = json_decode($request->input('selection'), true);

            $successCount = 0;
            $failedCount = 0;
            $messageText = '';
            if ($action == 'MarkAsRead') {

                foreach ($selections as $notificationId) {
                    $notification = Notification::query()->findOrFail($notificationId);

                    if (user()->cant('update', $notification)) {
                        $failedCount++;
                        continue;
                    }

                    $successCount++;
                    $notification->markAsRead();
                }
            }

            if ($successCount) {
                $messageText .= trans_choice('Notification::validation.messages.notification.success_record',
                        $successCount, ['value' => $successCount, 'action' => $action]) . ', ';
            }

            if ($failedCount) {
                $messageText .= trans_choice('Notification::validation.messages.notification.record_failed',
                        $failedCount, ['value' => $failedCount, 'action' => $action]) . ', ';
            }

            if (!$successCount && !$failedCount) {
                $messageText .= trans('Notification::validation.messages.notification.no_record_selected') . ', ';
            }

            $message = ['level' => 'info', 'message' => $messageText];
        } catch (\Exception $exception) {
            log_exception($exception);
            $message[] = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message ?? [['level' => 'info', 'message' => trans('Notification::validation.messages.notification.no_action_to_do')]]);
}

}
