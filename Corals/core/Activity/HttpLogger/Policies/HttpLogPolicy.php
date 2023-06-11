<?php

namespace Corals\Activity\HttpLogger\Policies;

use Corals\Activity\HttpLogger\Models\HttpLog;
use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;

class HttpLogPolicy extends BasePolicy
{

    /**
     * @param User $user
     * @param HttpLog|null $httpLog
     * @return bool
     */
    public function view(User $user, HttpLog $httpLog = null)
    {
        if ($user->can('Activity::http_log.view')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param HttpLog $httpLog
     * @return bool
     */
    public function destroy(User $user, HttpLog $httpLog)
    {
        if ($user->can('Activity::http_log.delete')) {
            return true;
        }
        return false;
    }
}
