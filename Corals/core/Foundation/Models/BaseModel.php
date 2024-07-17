<?php

namespace Corals\Foundation\Models;

use Corals\Foundation\Traits\BaseRelations;
use Corals\Foundation\Traits\HashTrait;
use Corals\Foundation\Traits\Hookable;
use Corals\Foundation\Traits\Language\Translatable;
use Corals\Foundation\Traits\ModelActionsTrait;
use Corals\Foundation\Traits\ModelHelpersTrait;
use Corals\Foundation\Traits\ModelPropertiesTrait;
use Corals\Settings\Traits\CustomFieldsModelTrait;
use Corals\Settings\Traits\HasSettings;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Yajra\Auditable\AuditableTrait;

class BaseModel extends Model
{
    use HashTrait, AuditableTrait, Hookable, CustomFieldsModelTrait, ModelHelpersTrait,
        ModelActionsTrait, Translatable, HasSettings, BaseRelations, ModelPropertiesTrait, LogsActivity;

    public $allowPublicSearch = false;

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

    /**
     * @return string
     */
    public function getThumbnailAttribute()
    {
        $media = $this->getFirstMedia($this->mediaCollectionName);

        if ($media) {
            return $media->getFullUrl();
        } elseif ($thumbnailLink = $this->getProperty('thumbnail_link')) {
            return $thumbnailLink;
        } else {
            return asset(config($this->config . '.default_image'));
        }
    }

    public function saveQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }

    protected function isGuardableColumn($key)
    {
        return parent::isGuardableColumn($key) || $this->hasSetMutator($key);
    }

    public function AggregatedRatingParentModel()
    {
        return null;
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return (new LogOptions)
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->dontLogIfAttributesChangedOnly(['id', 'created_by', 'updated_by', 'updated_at', 'created_at', 'deleted_at']);
    }

    public function customActivityLog($logName, $description, $attributes = [], $old = [], $custom = []): void
    {
        activity($logName)->performedOn($this)
            ->withProperties([
                'attributes' => $attributes,
                'old' => $old,
                'custom' => $custom
            ])->log($description);
    }
}
