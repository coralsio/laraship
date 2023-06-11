<?php

namespace Corals\Settings\Models;

use Corals\Foundation\Models\BaseModel;

class Country extends BaseModel
{
    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'settings.models.models.country';


    protected $guarded = ['id'];

    protected $casts = [
        'properties' => 'json'
    ];
}
