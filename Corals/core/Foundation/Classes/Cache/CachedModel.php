<?php

namespace Corals\Foundation\Classes\Cache;

use Corals\Foundation\Traits\Cache\Cachable;
use Corals\Foundation\Traits\ModelHelpersTrait;
use Illuminate\Database\Eloquent\Model;

abstract class CachedModel extends Model
{
    use Cachable, ModelHelpersTrait;
}
