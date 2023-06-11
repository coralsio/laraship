<?php

namespace Corals\Settings\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Settings\Models\CustomFieldSetting;

class CustomFieldSettingTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('settings.models.custom_field_setting.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param CustomFieldSetting $setting
     * @return array
     * @throws \Throwable
     */
    public function transform(CustomFieldSetting $setting)
    {
        $transformedArray = [
            'id' => $setting->id,
            'model' => class_basename($setting->getAttribute('model')),
            'created_at' => format_date($setting->created_at),
            'updated_at' => format_date($setting->updated_at),
            'action' => $this->actions($setting)
        ];

        return parent::transformResponse($transformedArray);
    }
}