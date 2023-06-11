<?php

namespace Corals\User\Communication\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class NotificationTemplatePresenter extends FractalPresenter
{

    /**
     * @return NotificationTemplateTransformer
     */
    public function getTransformer()
    {
        return new NotificationTemplateTransformer();
    }
}