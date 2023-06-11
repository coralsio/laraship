<?php

namespace Corals\Settings\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Traits\Cache\Cachable;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\Settings\Traits\DynamicFieldsModel;

class CustomFieldSetting extends BaseModel
{
    use PresentableTrait, Cachable, DynamicFieldsModel;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'settings.models.custom_field_setting';

    protected $casts = [
        'fields' => 'json',
        'properties' => 'json'
    ];

    protected $guarded = ['id'];
}
