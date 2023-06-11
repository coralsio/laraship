<?php

namespace Corals\Settings\Classes;


class CustomFields
{
    /**
     * @param $object
     * @return \Illuminate\Support\Collection
     */
    public function getSortedFields($object)
    {
        $fields = is_object($object) ? $object->fields : $object;

        return collect($fields)
            ->sortBy(function ($field) {
                return $this->getAttribute($field, 'field_config.order', INF);
            });
    }

    /**
     * @param $column
     * @param $needle
     * @param null $default
     * @return mixed
     */
    public function getAttribute($column, $needle, $default = null)
    {
        return data_get($column, $needle, $default) ?? $default;
    }
}
