<?php

namespace Corals\Foundation\Search;

interface SearchInterface
{
    public function run($search, $config);

    public function runForClass($search, $class, $config);

    public function searchQuery($search, $config);
}
