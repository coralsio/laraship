<?php

if (!function_exists('user')) {
    /**
     * @return \Corals\User\Models\User
     */
    function user()
    {
        return \Auth::user();
    }
}

if (!function_exists('isSuperUser')) {
    function isSuperUser(\Corals\User\Models\User $user = null)
    {
        if (is_null($user)) {
            $user = user();
        }

        if (!$user) {
            return false;
        }

        $superuser_id = \Settings::get('super_user_id', 1);

        return $user->id == $superuser_id || $user->hasRole('superuser');
    }
}

