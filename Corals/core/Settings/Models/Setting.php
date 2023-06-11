<?php

namespace Corals\Settings\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Traits\Cache\Cachable;
use Corals\Foundation\Transformers\PresentableTrait;

class Setting extends BaseModel
{
    use PresentableTrait, Cachable;

    public $htmlentitiesExcluded = ['value'];

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'settings.models.setting';

    protected $guarded = ['id'];

    protected $casts = [
        'editable' => 'boolean',
        'hidden' => 'boolean',
        'properties' => 'json'
    ];

    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = \Str::slug($value, '_');
    }

    public function getTypeAttribute()
    {
        return $this->attributes['type'] = strtoupper($this->attributes['type']);
    }

    public function getFilePath()
    {
        return config('settings.upload_path') . '/' . $this->attributes['value'];
    }

    public function scopeVisible($query)
    {
        return $query->where('hidden', '=', 0);
    }

    public function setValueAttribute($value)
    {
        switch ($this->attributes['type']) {
            case 'SELECT':
                $this->attributes['value'] = is_array($value) ? json_encode($value ?? []) : $value;
                break;
            default:
                $this->attributes['value'] = $value;
        }
    }

    public function getValueAttribute()
    {
        $value = $this->attributes['value'];

        switch ($this->attributes['type']) {
            case 'FILE':
                if (!empty($value)) {
                    return asset($this->getFilePath());
                }
                break;
            case 'SELECT':
                $values = json_decode($value, true);
                if ($values) {
                    return $values;
                } else {
                    return [];
                }
                break;
            case 'BOOLEAN':
                if ($value == 'true') {
                    return true;
                } else {
                    return false;
                }
                break;
            case 'NUMBER':
                return floatval($value);
                break;
        }

        return $value;
    }
}
