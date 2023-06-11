<?php

namespace Corals\Foundation\Contracts;

/**
 * Interface PresenterInterface
 * @package Corals\Foundation\Contracts
 */
interface PresenterInterface
{
    /**
     * Prepare data to present
     *
     * @param $data
     *
     * @return mixed
     */
    public function present($data);
}
