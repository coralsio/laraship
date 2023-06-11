<?php

namespace Corals\Activity\HttpLogger\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\User\Models\User;

class HttpLog extends BaseModel
{
    use PresentableTrait;

    protected $table = 'http_log';

    protected $config = 'http_logger.models.http_log';

    protected $casts = [
        'headers' => 'json',
        'body' => 'json',
        'response' => 'json',
        'files' => 'json',
        'properties' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}