<?php

namespace Corals\Foundation\Search\Commands;

use Illuminate\Console\Command;
use Corals\Foundation\Search\Indexer;

class UnindexOne extends Command
{

    protected $signature = 'corals-search:unindex {model_class} {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove a single record from the searchindex';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $indexer = new Indexer();
        $indexer->unIndexOneByClass($this->argument('model_class'), $this->argument('id'));
    }
}
