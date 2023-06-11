<?php

namespace Corals\Settings\Transformers\API;

use Corals\Foundation\Transformers\FractalPresenter;

class SettingPresenter extends FractalPresenter
{

    /**
     * @return SettingTransformer
     */
    public function getTransformer()
    {
        return new SettingTransformer();
    }
}