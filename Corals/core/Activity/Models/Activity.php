<?php

namespace Corals\Activity\Models;

use Corals\Foundation\Traits\HashTrait;
use Corals\Foundation\Traits\Hookable;
use Corals\Foundation\Traits\ModelActionsTrait;
use Corals\Foundation\Traits\ModelHelpersTrait;
use Corals\Foundation\Traits\ModelPropertiesTrait;
use Corals\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    use HashTrait, Hookable, ModelHelpersTrait, ModelActionsTrait, PresentableTrait, ModelPropertiesTrait;

    public $config = 'activity.models.activity';

    public function __construct(array $attributes = [])
    {
        $config = config($this->config);

        if (isset($config['presenter'])) {
            $this->setPresenter(new $config['presenter']);
            unset($config['presenter']);
        }

        foreach ($config as $key => $val) {
            if (property_exists(get_called_class(), $key)) {
                $this->$key = $val;
            }
        }

        return parent::__construct($attributes);
    }

    protected $casts = [
        'properties' => 'collection'
    ];
}
