<?php

namespace Corals\User\Facades;

use Corals\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Corals\User\Classes\CoralsAuthentication as CoralsAuthenticationClass;

/**
 * @method static login(User $user, array &$userDetails)
 * @method static logout()
 * @method static sendConfirmationToUser(Model $user)
 */
class CoralsAuthentication extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return CoralsAuthenticationClass::class;
    }
}
