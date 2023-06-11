<?php

namespace Corals\User\Models;

use Corals\Foundation\Traits\HashTrait;
use Corals\Foundation\Traits\Hookable;
use Corals\Foundation\Traits\ModelPropertiesTrait;
use Corals\Settings\Traits\CustomFieldsModelTrait;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HashTrait, Hookable, LogsActivity, ModelPropertiesTrait, CustomFieldsModelTrait;

    protected $casts = [
        'properties' => 'json'
    ];

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return (new LogOptions)
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->dontLogIfAttributesChangedOnly(['id', 'updated_at', 'created_at', 'deleted_at']);
    }

}
