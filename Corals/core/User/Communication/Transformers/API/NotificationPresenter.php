<?php

namespace Corals\User\Communication\Transformers\API;

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
