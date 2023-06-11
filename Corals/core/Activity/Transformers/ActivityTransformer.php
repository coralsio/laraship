<?php

namespace Corals\Activity\Transformers;

use Corals\Activity\Models\Activity;
use Corals\Foundation\Transformers\BaseTransformer;

class ActivityTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('activity.models.activity.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Activity $activity
     * @return array
     * @throws \Throwable
     */
    public function transform(Activity $activity)
    {
        $object_url = '#';

        if ($activity->subject_type && $activity->subject_id) {
            $subject = $activity->subject;

            if ($subject) {
                $config = config($subject->config);

                if ($config && \Arr::has($config, 'resource_url')) {
                    $object_url = url($config['resource_url'] . '/' . hashids_encode($activity->subject_id));
                }
            }
        }

        $transformedArray = [
            'id' => $activity->id,
            'checkbox' => $this->generateCheckboxElement($activity),
            'log_name' => ucwords($activity->log_name),
            'subject_type' => $activity->subject_type ? class_basename($activity->subject_type) : '-',
            'subject_id' => "<a target='_blank' href='{$object_url}'>{$activity->subject_id}</a>",
            'causer_id' => $activity->causer ? $activity->causer->present('identifier') : '-',
            'description' => strlen($activity->description) > 70 ? generatePopover($activity->description) : $activity->description,
            'properties' => generatePopover(formatProperties($activity->properties)),
            'created_at' => format_date_time($activity->created_at),
            'updated_at' => format_date($activity->updated_at),
            'action' => $this->actions($activity)
        ];

        return parent::transformResponse($transformedArray);
    }
}
