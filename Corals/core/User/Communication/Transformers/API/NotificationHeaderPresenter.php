<?php

namespace Corals\User\Communication\Transformers\API;

use Corals\Foundation\Transformers\FractalPresenter;

class NotificationHeaderPresenter extends FractalPresenter
{
    /**
     * @return NotificationHeaderTransformer
     */
    public function getTransformer()
    {
        return new NotificationHeaderTransformer();
    }
}
