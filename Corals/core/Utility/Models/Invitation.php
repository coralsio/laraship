<?php

namespace Corals\Utility\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\User\Models\User;

class Invitation extends BaseModel
{
    use PresentableTrait;

    /**
     *  Model configuration.
     * @var string
     */

    protected $casts = [
        'properties' => 'json',
    ];

    protected $table = 'utility_invites';

    protected $guarded = ['id'];

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function invalidateInvitationCode($accepted)
    {
        $this->update([
            'code' => '##########' . $this->id,
            'accepted' => $accepted,
        ]);
    }

    public static function isValid($code)
    {
        return substr($code, 0, 10) !== '##########';
    }


}
