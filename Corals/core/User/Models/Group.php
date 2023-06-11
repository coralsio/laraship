<?php

namespace Corals\User\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Group extends BaseModel
{
    use PresentableTrait, LogsActivity, SoftDeletes;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'user.models.group';

    protected $casts = [
        'properties' => 'json',
    ];

    protected $guarded = ['id'];

    public function Users()
    {
        return $this->belongsToMany(User::class);
    }
}


