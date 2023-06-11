<?php

namespace Corals\Elfinder\Providers;

use Corals\Foundation\Providers\BaseUpdateModuleServiceProvider;

class UpdateModuleServiceProvider extends BaseUpdateModuleServiceProvider
{
    protected $module_code = 'corals-file-manager';
    protected $batches_path = __DIR__ . '/../update-batches/*.php';
    protected $module_public_path = __DIR__ . '/../public';
}
