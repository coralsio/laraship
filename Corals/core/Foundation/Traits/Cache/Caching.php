<?php

namespace Corals\Foundation\Traits\Cache;

use Corals\Foundation\Classes\Cache\CacheKey;
use Corals\Foundation\Classes\Cache\CacheTags;
use Illuminate\Cache\TaggableStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

trait Caching
{
    protected $isCachable = true;

    /**
     * @param array $tags
     * @return \Illuminate\Cache\CacheManager|\Illuminate\Contracts\Cache\Repository|mixed
     * @throws \Exception
     */
    public function cache(array $tags = [])
    {
        $cache = cache();

        if (config('laravel-model-caching.store')) {
            $cache = $cache->store(config('laravel-model-caching.store'));
        }

        if (is_subclass_of($cache->getStore(), TaggableStore::class)) {
            $cache = $cache->tags($tags);
        }

        return $cache;
    }

    public function disableModelCaching()
    {
        $this->isCachable = false;

        return $this;
    }

    /**
     * @param array $tags
     * @throws \Exception
     */
    public function flushCache(array $tags = [])
    {
        if (count($tags) === 0) {
            $tags = $this->makeCacheTags();
        }

        $this->cache($tags)->flush();

        list($cacheCooldown) = $this->getModelCacheCooldown($this);

        if ($cacheCooldown) {
            $cachePrefix = $this->getCachePrefix();
            $modelClassName = get_class($this);
            $cacheKey = "{$cachePrefix}:{$modelClassName}-cooldown:saved-at";

            $this->cache()
                ->rememberForever($cacheKey, function () {
                    return now();
                });
        }
    }

    protected function getCachePrefix(): string
    {
        return "genealabs:laravel-model-caching:"
            . (config('laravel-model-caching.cache-prefix')
                ? config('laravel-model-caching.cache-prefix', '') . ":"
                : "");
    }

    protected function makeCacheKey(
        array $columns = ['*'],
        $idColumn = null,
        string $keyDifferentiator = ''
    ): string
    {
        $eagerLoad = $this->eagerLoad ?? [];
        $model = $this->model ?? $this;
        $query = $this->query ?? app(Builder::class);

        return (new CacheKey($eagerLoad, $model, $query))
            ->make($columns, $idColumn, $keyDifferentiator);
    }

    protected function makeCacheTags(): array
    {
        $eagerLoad = $this->eagerLoad ?? [];
        $model = $this->model ?? $this;

        if (!($model instanceof Model)) {
            try {
                $model = app($model);
            } catch (\Exception $exception) {
                $model = $this;
            }
        }

        $query = $this->query ?? app(Builder::class);

        $tags = (new CacheTags($eagerLoad, $model, $query))
            ->make();

        return $tags;
    }

    public function getModelCacheCooldown(Model $instance)
    {
        $cachePrefix = $this->getCachePrefix();
        $modelClassName = get_class($instance);
        list($cacheCooldown, $invalidatedAt, $savedAt) = $this
            ->getCacheCooldownDetails($instance, $cachePrefix, $modelClassName);

        if (!$cacheCooldown || $cacheCooldown === 0) {
            return [null, null, null];
        }

        return [
            $cacheCooldown,
            $invalidatedAt,
            $savedAt,
        ];
    }

    /**
     * @param Model $instance
     * @param string $cachePrefix
     * @param string $modelClassName
     * @return array
     */
    protected function getCacheCooldownDetails(
        Model $instance,
        string $cachePrefix,
        string $modelClassName
    ): array
    {
        return [
            $instance
                ->cache()
                ->get("{$cachePrefix}:{$modelClassName}-cooldown:seconds"),
            $instance
                ->cache()
                ->get("{$cachePrefix}:{$modelClassName}-cooldown:invalidated-at"),
            $instance
                ->cache()
                ->get("{$cachePrefix}:{$modelClassName}-cooldown:saved-at"),
        ];
    }

    protected function checkCooldownAndRemoveIfExpired(Model $instance)
    {
        list($cacheCooldown, $invalidatedAt) = $this->getModelCacheCooldown($instance);

        if (!$cacheCooldown
            || now()->diffInSeconds($invalidatedAt) < $cacheCooldown
        ) {
            return;
        }

        $cachePrefix = $this->getCachePrefix();
        $modelClassName = get_class($instance);

        $instance
            ->cache()
            ->forget("{$cachePrefix}:{$modelClassName}-cooldown:invalidated-at");
        $instance
            ->cache()
            ->forget("{$cachePrefix}:{$modelClassName}-cooldown:invalidated-at");
        $instance
            ->cache()
            ->forget("{$cachePrefix}:{$modelClassName}-cooldown:saved-at");
        $instance->flushCache();
    }

    protected function checkCooldownAndFlushAfterPersiting(Model $instance)
    {
        list($cacheCooldown, $invalidatedAt) = $instance->getModelCacheCooldown($instance);

        if (!$cacheCooldown) {
            $instance->flushCache();

            return;
        }

        $this->setCacheCooldownSavedAtTimestamp($instance);

        if (now()->diffInSeconds($invalidatedAt) >= $cacheCooldown) {
            $instance->flushCache();
        }
    }

    public function isCachable(): bool
    {
        return $this->isCachable
            && !config('laravel-model-caching.disabled');
    }

    protected function setCacheCooldownSavedAtTimestamp(Model $instance)
    {
        $cachePrefix = $this->getCachePrefix();
        $modelClassName = get_class($instance);
        $cacheKey = "{$cachePrefix}:{$modelClassName}-cooldown:saved-at";

        $instance->cache()
            ->rememberForever($cacheKey, function () {
                return now();
            });
    }
}
