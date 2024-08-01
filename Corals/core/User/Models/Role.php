<?php

namespace Corals\User\Models;

use Corals\Foundation\Traits\HashTrait;
use Corals\Foundation\Traits\Hookable;
use Corals\Foundation\Traits\Language\Translatable;
use Corals\Foundation\Traits\ModelActionsTrait;
use Corals\Foundation\Traits\ModelHelpersTrait;
use Corals\Foundation\Traits\ModelPropertiesTrait;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\Settings\Traits\CustomFieldsModelTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role as SpatieRole;
use Yajra\Auditable\AuditableTrait;
use  Spatie\Activitylog\LogOptions;


class Role extends SpatieRole
{
    use PresentableTrait, LogsActivity, HashTrait, AuditableTrait,
        Hookable, CustomFieldsModelTrait, Translatable, ModelPropertiesTrait, ModelActionsTrait, ModelHelpersTrait;
    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'user.models.role';

    protected $casts = [
        'properties' => 'json',
        'can_manage_roles' => 'json'
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
