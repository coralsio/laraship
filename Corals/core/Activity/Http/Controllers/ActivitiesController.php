<?php

namespace Corals\Activity\Http\Controllers;

use Corals\Activity\DataTables\ActivitiesDataTable;
use Corals\Activity\Http\Requests\ActivityRequest;
use Corals\Activity\Models\Activity;
use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Foundation\Http\Requests\BulkRequest;

class ActivitiesController extends BaseController
{

    public function __construct()
    {
        $this->resource_url = config('activity.models.activity.resource_url');

        $this->resource_model = new Activity();

        $this->title = 'Activity::module.activity.title';
        $this->title_singular = 'Activity::module.activity.title_singular';

        parent::__construct();
    }

    /**
     * @param ActivityRequest $request
     * @param ActivitiesDataTable $dataTable
     * @return mixed
     */
    public function index(ActivityRequest $request, ActivitiesDataTable $dataTable)
    {
        return $dataTable->render('Activity::activities.index');
    }

    /**
     * @param Activity $activity
     * @return Activity
     */
    public function show(Activity $activity)
    {
        return $activity;
    }

    /**
     * @param ActivityRequest $request
     * @param $model_name
     * @param $model_hashed_id
     * @return array|string
     * @throws \Throwable
     */
    public function showModelActivities(ActivityRequest $request, $model_name, $model_hashed_id)
    {

        abort_if(!$request->ajax(), 404);

        $model_name = str_replace("-", "\\", $model_name);

        $activities = Activity::query()
            ->where('subject_type', getMorphAlias($model_name))
            ->where('subject_id', hashids_decode($model_hashed_id))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Activity::activities.show_model_activities')->with(compact('activities'))->render();
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
            switch ($action) {
                case 'delete' :
                    foreach ($selection as $selection_id) {
                        $activity = Activity::findByHash($selection_id);
                        $activity_request = new ActivityRequest;
                        $activity_request->setMethod('DELETE');
                        $this->destroy($activity_request, $activity);
                    }
                    $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])];
                    break;
            }
        } catch (\Exception $exception) {
            log_exception($exception, Activity::class, 'bulkAction');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }
        return response()->json($message);
    }

    /**
     * @param ActivityRequest $request
     * @param Activity $activity
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ActivityRequest $request, Activity $activity)
    {
        try {
            $activity->delete();

            $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])];
        } catch (\Exception $exception) {
            log_exception($exception, Activity::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}
