<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('broadcasting.user.{user}', function ($authUser, \Corals\User\Models\User $user) {
    return $authUser->id === $user->id;
});
