<?php

namespace Corals\Settings\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;

class Module extends BaseModel
{
    use PresentableTrait;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'settings.models.module';

    protected $casts = [
        'installed' => 'boolean',
        'enabled' => 'boolean',
        'properties' => 'json'
    ];

    protected $guarded = ['id'];

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeInstalled($query)
    {
        return $query->where('installed', true);
    }
}
