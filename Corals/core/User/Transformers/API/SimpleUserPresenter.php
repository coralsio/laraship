<?php

namespace Corals\User\Transformers\API;

use Corals\Foundation\Transformers\FractalPresenter;

class SimpleUserPresenter extends FractalPresenter
{

    /**
     * @return SimpleUserTransformer
     */
    public function getTransformer()
    {
        return new SimpleUserTransformer();
    }
}