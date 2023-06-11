<?php

namespace Corals\Settings\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Settings\Models\Setting;

class SettingTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('settings.models.setting.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Setting $setting
     * @return array
     * @throws \Throwable
     */
    public function transform(Setting $setting)
    {
        switch ($setting->type) {
            case "SELECT":
                $setting_value = json_decode($setting->getRawOriginal('value'), true);
                $setting_display = is_array($setting_value) ? formatArrayAsLabels($setting_value,
                    'info') : $setting_value;
                break;
            case "FILE":
                $setting_display = '<a target="_blank" href="' . asset($setting->getFilePath()) . '">' . $setting->getRawOriginal('value') . '</a>';
                break;
            default:
                $setting_display = \Str::limit(strip_tags($setting->getRawOriginal('value')), 50);
                break;
        }


        $transformedArray = [
            'id' => $setting->id,
            'code' => $setting->code,
            'type' => $setting->type,
            'label' => $setting->label,
            'value' => $setting_display ?? '-',
            'created_at' => format_date($setting->created_at),
            'updated_at' => format_date($setting->updated_at),
            'action' => $this->actions($setting)
        ];

        return parent::transformResponse($transformedArray);
    }
}
