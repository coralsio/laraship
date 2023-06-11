<?php

namespace Corals\Foundation\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ModelCacheFlush extends Command
{
    protected $signature = 'modelCache:flush {--model=}';
    protected $description = 'Flush cache for a given model. If no model is given, entire model-cache is flushed.';

    public function handle()
    {
        $option = $this->option('model');

        if (!$option) {
            return $this->flushEntireCache();
        }

        return $this->flushModelCache($option);
    }

    protected function flushEntireCache(): int
    {
        cache()
            ->store(config('laravel-model-caching.store'))
            ->flush();

        $this->info("✔︎ Entire model cache has been flushed.");

        return 0;
    }

    protected function flushModelCache(string $option): int
    {
        $model = new $option;
        $usesCachableTrait = $this->getAllTraitsUsedByClass($option)
            ->contains("Corals\Foundation\Traits\Cachable");

        if (!$usesCachableTrait) {
            $this->error("'{$option}' is not an instance of CachedModel.");
            $this->line("Only CachedModel instances can be flushed.");

            return 1;
        }

        $model->flushCache();
        $this->info("✔︎ Cache for model '{$option}' has been flushed.");

        return 0;
    }

    protected function getAllTraitsUsedByClass(
        string $classname,
        bool $autoload = true
    ): Collection
    {
        $traits = collect();

        if (class_exists($classname, $autoload)) {
            $traits = collect(class_uses($classname, $autoload));
        }

        $parentClass = get_parent_class($classname);

        if ($parentClass) {
            $traits = $traits
                ->merge($this->getAllTraitsUsedByClass($parentClass, $autoload));
        }

        return $traits;
    }
}
