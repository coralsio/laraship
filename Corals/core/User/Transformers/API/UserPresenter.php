<?php

namespace Corals\User\Transformers\API;

use Corals\Foundation\Transformers\FractalPresenter;

class UserPresenter extends FractalPresenter
{

    /**
     * @return UserTransformer
     */
    public function getTransformer()
    {
        return new UserTransformer();
    }
}