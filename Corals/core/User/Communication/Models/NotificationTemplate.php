<?php

namespace Corals\User\Communication\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Spatie\Permission\Traits\HasRoles;


/**
 * Class NotificationTemplate
 * @package Corals\User\Communication\Models
 * @property integer id
 * @property string name
 * @property string title
 * @property array body
 * @property array via
 * @property array extras
 * @property array forced_channels
 */
class NotificationTemplate extends BaseModel
{

    use HasRoles, PresentableTrait; // for morph relation with model_has_roles table

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'notification.models.notification_template';

    protected $guarded = ['id'];

    protected $casts = [
        'body' => 'array',
        'extras' => 'array',
        'via' => 'array',
    ];

    public function getForcedChannelsAttribute()
    {
        return $this->via ? array_diff($this->via, ['user_preferences']) : [];
    }
}
