<?php

namespace Corals\Settings\Transformers\API;

use Corals\Foundation\Transformers\APIBaseTransformer;
use Corals\Settings\Models\Setting;

class SettingTransformer extends APIBaseTransformer
{
    /**
     * @param Setting $setting
     * @return array
     */
    public function transform(Setting $setting)
    {
        $transformedArray = [
            'code' => $setting->code,
            'type' => $setting->type,
            'label' => $setting->label,
            'value' => $setting->getRawOriginal('value'),
        ];

        return parent::transformResponse($transformedArray);
    }
}