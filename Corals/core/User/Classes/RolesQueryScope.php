<?php

namespace Corals\User\Classes;

use Illuminate\Database\Eloquent\Builder;

class RolesQueryScope
{
    public function apply(Builder $query, $params)
    {
        $query->whereHas('roles', function ($r) use ($params) {
            $r->whereIn('name', $params);
        });
    }
}
