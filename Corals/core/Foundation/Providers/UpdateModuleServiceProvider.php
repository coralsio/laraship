<?php

namespace Corals\Foundation\Providers;

class UpdateModuleServiceProvider extends BaseUpdateModuleServiceProvider
{
    protected $module_code = 'corals-foundation';
    protected $batches_path = __DIR__ . '/../update-batches/*.php';
    protected $module_public_path = __DIR__ . '/../public';
}
