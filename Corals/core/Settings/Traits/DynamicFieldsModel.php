<?php


namespace Corals\Settings\Traits;


trait DynamicFieldsModel
{

    public function getCustomAttributesAttribute()
    {
        return getKeyValuePairs($this->attributes['custom_attributes'] ?? []);
    }

    public function getOptionsAttribute()
    {
        return getKeyValuePairs($this->attributes['options'] ?? []);
    }
}