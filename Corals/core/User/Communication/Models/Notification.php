<?php

namespace Corals\User\Communication\Models;


use Corals\Foundation\Traits\ModelActionsTrait;
use Corals\Foundation\Traits\ModelHelpersTrait;
use Corals\Foundation\Transformers\PresentableTrait;
use Illuminate\Notifications\DatabaseNotification;

/**
 * Class Notification
 * @package Corals\User\Communication\Models
 * @property integer type
 * @property integer notifiable_id
 * @property string notifiable_type
 * @property array data
 * @property string read_at
 * @method markAsUnread()
 * @method read()
 * @method unread()
 *
 */
class Notification extends DatabaseNotification
{
    use ModelActionsTrait, ModelHelpersTrait, PresentableTrait;

    public $config = 'notification.models.notification';

    protected static function boot()
    {
        parent::boot();
    }

    /**
     * BaseModel constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->initialize();

        return parent::__construct($attributes);
    }

    /**
     * init model
     */
    public function initialize()
    {
        $config = config($this->config);

        if ($config) {
        if (isset($config['presenter'])) {
            $this->setPresenter(new $config['presenter']);
            unset($config['presenter']);
        }

        foreach ($config as $key => $val) {
            if (property_exists(get_called_class(), $key)) {
                $this->$key = $val;
            }
        }
        }
    }

    protected $casts = [
        'data' => 'array'
    ];

    /**
     * Toggle Read at of a notification
     *
     */
    public function toggleReadAt()
    {
        $this->unread() ? $this->markAsRead() : $this->markAsUnread();
    }
}
