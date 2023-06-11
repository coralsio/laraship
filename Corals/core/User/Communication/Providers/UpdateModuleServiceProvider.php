<?php

namespace Corals\User\Communication\Providers;

use Corals\Foundation\Providers\BaseUpdateModuleServiceProvider;

class UpdateModuleServiceProvider extends BaseUpdateModuleServiceProvider
{
    protected $module_code = 'corals-notification';
    protected $batches_path = __DIR__ . '/../update-batches/*.php';
}
