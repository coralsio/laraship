<?php

namespace Corals\Settings\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class SettingPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return SettingTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new SettingTransformer($extras);
    }
}