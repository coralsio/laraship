<?php

namespace Corals\User\Communication\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class NotificationPresenter extends FractalPresenter
{

    /**
     * @return NotificationTransformer
     */
    public function getTransformer()
    {
        return new NotificationTransformer();
    }
}