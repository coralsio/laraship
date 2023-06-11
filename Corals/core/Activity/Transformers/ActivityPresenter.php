<?php

namespace Corals\Activity\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class ActivityPresenter extends FractalPresenter
{

    /**
     * @return ActivityTransformer
     */
    public function getTransformer()
    {
        return new ActivityTransformer();
    }
}