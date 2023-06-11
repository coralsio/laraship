<?php

namespace Corals\User\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class GroupPresenter extends FractalPresenter
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