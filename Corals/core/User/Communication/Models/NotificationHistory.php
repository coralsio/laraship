<?php

namespace Corals\User\Communication\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Traits\ModelHelpersTrait;
use Corals\Foundation\Traits\ModelPropertiesTrait;
use Corals\Foundation\Transformers\PresentableTrait;
use Illuminate\Database\Eloquent\Builder;

class NotificationHistory extends BaseModel
{
    use PresentableTrait, ModelHelpersTrait, ModelPropertiesTrait;

    public $config = 'notification.models.notification_history';

    protected $table = 'notifications_history';

    protected $guarded = ['id'];

    protected $casts = [
        'properties' => 'json',
        'body' => 'json',
        'channels' => 'json',
        'notifiables' => 'json'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * @param Builder $builder
     * @param $notificationName
     */
    public function scopeFor(Builder $builder, $notificationName): void
    {
        $builder->where('notification_name', $notificationName);
    }
}
