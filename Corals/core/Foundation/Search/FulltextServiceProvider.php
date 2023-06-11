<?php

namespace Corals\Foundation\Search;

use Illuminate\Support\ServiceProvider;
use Corals\Foundation\Search\Commands\Index;
use Corals\Foundation\Search\Commands\IndexOne;
use Corals\Foundation\Search\Commands\Install;
use Corals\Foundation\Search\Commands\UnindexOne;

class FulltextServiceProvider extends ServiceProvider
{

    protected $commands = [
        Index::class,
        IndexOne::class,
        UnindexOne::class,
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }

        $this->app->bind(
            SearchInterface::class,
            Search::class
        );
    }
}
