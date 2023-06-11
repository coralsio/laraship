<?php

namespace Corals\User\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class RolePresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return RoleTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new RoleTransformer($extras);
    }
}