<?php

namespace Corals\Foundation\Transformers;

use Corals\Foundation\Contracts\PresenterInterface;
use Illuminate\Support\Arr;

/**
 * Trait PresentableTrait
 * @package Corals\Foundation\Transformers
 */
trait PresentableTrait
{

    protected $presenter = null;
    protected $presenterData = null;
    protected $extras = ['include-action' => false];

    /**
     * @param PresenterInterface $presenter
     * @return $this
     */
    public function setPresenter(PresenterInterface $presenter)
    {
        $this->presenter = $presenter;

        return $this;
    }

    /**
     * @param      $key
     * @param null $default
     *
     * @return mixed|null
     */
    public function present($key, $default = null)
    {
        $value = $default;

        if ($key == 'action') {
            $this->extras['include-action'] = true;
            $this->presenterData = null;
        }
        if ($this->hasPresenter()) {
            $data = $this->presenter();

            $value = Arr::get($data, $key, $default);
        }

        if (is_null($value)) {
            $value = $this->{$key};
        }

        return $value;
    }


    public function presentStripTags($key, $default = null)
    {
        $value = $this->present($key, $default);

        return strip_tags($value);
    }

    /**
     * @return bool
     */
    protected function hasPresenter()
    {
        return isset($this->presenter) && $this->presenter instanceof PresenterInterface;
    }

    /**
     * @return $this|mixed
     */
    public function presenter($extras = [])
    {
        if (!is_null($this->presenterData)) {
            return $this->presenterData['data'] ?? $this->presenterData;
        }

        $this->extras = array_merge($this->extras, $extras);

        if ($this->hasPresenter()) {
            $presenter = $this->presenter->present($this, $this->extras);
            $this->presenterData = $presenter;
            return $presenter['data'] ?? $presenter;
        }

        return $this;
    }

    public function resetPresenterData()
    {
        $this->presenterData = null;
    }
}
