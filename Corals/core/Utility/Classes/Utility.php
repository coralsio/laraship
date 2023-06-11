<?php

namespace Corals\Utility\Classes;

class Utility
{
    protected $utilityModules = [];

    public function addToUtilityModules($module)
    {
        array_push($this->utilityModules, $module);
    }

    public function getUtilityModules()
    {
        return array_combine($this->utilityModules, $this->utilityModules);
    }
}
