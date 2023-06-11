<?php

namespace Corals\Settings\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class CustomFieldSettingPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return CustomFieldSettingTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new CustomFieldSettingTransformer($extras);
    }
}