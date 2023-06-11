<?php

namespace Corals\Foundation\Models;

use Spatie\Activitylog\Traits\LogsActivity;

class GatewayStatus extends BaseModel

{
    use LogsActivity;

    protected $guarded = ['id'];

    protected $table = 'gateway_status';

    protected $casts = [
        'properties' => 'json',
    ];

    public function ObjectType()
    {
        return $this->morphTo();
    }

    /**
     * @param $status
     * @param null $message
     */
    public function markAs($status, $message = null)
    {
        $this->fill(['status' => $status, 'message' => $message])->save();
    }
}
