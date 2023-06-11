<?php

namespace Corals\Activity\HttpLogger\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class HttpLogPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return HttpLoggerTransformer
     */
    public function getTransformer($extras = [])
    {
        return new HttpLoggerTransformer();
    }
}
