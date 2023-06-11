<?php

namespace Corals\Menu\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class MenuPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return MenuTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new MenuTransformer($extras);
    }
}