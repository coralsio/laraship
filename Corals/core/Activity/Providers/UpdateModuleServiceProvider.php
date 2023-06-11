<?php

namespace Corals\Activity\Providers;

use Corals\Foundation\Providers\BaseUpdateModuleServiceProvider;

class UpdateModuleServiceProvider extends BaseUpdateModuleServiceProvider
{
    protected $module_code = 'corals-activity';
    protected $batches_path = __DIR__ . '/../update-batches/*.php';
}
