<?php

namespace Corals\User\Observers;

use Corals\User\Models\User;

class UserObserver
{

    /**
     * @param User $user
     */
    public function created(User $user)
    {
    }

    /**
     * @param User $user
     * @throws \Exception
     */
    public function deleted(User $user)
    {

    }
}
