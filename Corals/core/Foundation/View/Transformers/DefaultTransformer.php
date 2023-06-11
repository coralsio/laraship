<?php

namespace Corals\Foundation\View\Transformers;

class DefaultTransformer
{
    public function transform($value)
    {
        return json_encode($value);
    }
}
