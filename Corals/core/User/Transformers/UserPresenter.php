<?php

namespace Corals\User\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class UserPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return UserTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new UserTransformer($extras);
    }
}