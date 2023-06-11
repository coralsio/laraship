<?php

namespace Corals\Foundation\Shortcodes\Service;

interface WidgetContractInterface
{
    /**
     * @param $key
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param mixed
     *
     * @return mixed
     */
    public function run();
}
