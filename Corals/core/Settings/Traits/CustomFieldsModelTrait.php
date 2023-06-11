<?php

namespace Corals\Settings\Traits;

use Corals\Foundation\Facades\CoralsForm;
use Corals\Settings\Models\CustomFieldSetting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

trait CustomFieldsModelTrait
{
    /**
     * @param string $status
     * @return \Illuminate\Support\Collection|mixed
     */
    public function customFieldSettings($status = 'active')
    {
        if (!schemaHasTable('custom_field_settings')) {
            return collect([]);
        }

        $className = getMorphAlias($this);

        $cache_key = Str::slug($className) . '_cf_settings';

        return Cache::remember($cache_key, 1440, function () use ($className, $status) {
            $customFields = CustomFieldSetting::where('model', $className)->first() ?? [];

            if ($customFields) {
                $modelCustomFields = $this->getActiveFields($customFields->fields);
            }

            if ($configCustomFields = $this->getConfig('custom_fields', [])) {
                $configCustomFields = $this->getActiveFields($configCustomFields);
            }

            return array_merge($modelCustomFields ?? [], $configCustomFields);
        });
    }

    /**
     * @param $customFields
     * @return array
     */

    private function getActiveFields($customFields): array
    {
        return array_filter($customFields, function ($customField) {
            return Arr::get($customField, 'status', 'inactive') == 'active';
        });
    }

    /**
     * @return array
     */
    public function getCustomFieldsValues($fieldClass = 'col-md-4'): array
    {
        $customFields = $this->customFieldSettings();
        $model = $this;
        $fields = [];

        foreach ($customFields as $field) {
            $name = CoralsForm::getCustomFieldName(Arr::get($field, 'name'));
            $name = str_replace('properties.', '', $name);
            $value = CoralsForm::getCustomFieldValue($model, $name, $propColumn = 'properties', $field);
            $fields [$name]['content'] = $value;
            $fields[$name]['grid_class'] = data_get($field, 'field_config.grid_class') ?? $fieldClass;
            $fields[$name]['type'] = data_get($field, 'type');
            $fields[$name]['label'] = data_get($field, 'label');
        }
        return $fields;
    }
}
