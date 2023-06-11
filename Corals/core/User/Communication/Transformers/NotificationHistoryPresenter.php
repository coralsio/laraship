<?php

namespace Corals\User\Communication\Transformers;


use Corals\Foundation\Transformers\FractalPresenter;

class NotificationHistoryPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return NotificationHistoryTransformer
     */
    public function getTransformer($extras = [])
    {
        return new NotificationHistoryTransformer($extras);
    }
}