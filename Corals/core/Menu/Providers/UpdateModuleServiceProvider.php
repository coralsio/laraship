<?php

namespace Corals\Menu\Providers;

use Corals\Foundation\Providers\BaseUpdateModuleServiceProvider;

class UpdateModuleServiceProvider extends BaseUpdateModuleServiceProvider
{
    protected $module_code = 'corals-menu';
    protected $batches_path = __DIR__ . '/../update-batches/*.php';
}
