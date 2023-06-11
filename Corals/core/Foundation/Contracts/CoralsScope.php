<?php

namespace Corals\Foundation\Contracts;


interface CoralsScope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     * @param $builder
     * @param array $extras
     * @return void
     */
    public function apply($builder, $extras = []);
}