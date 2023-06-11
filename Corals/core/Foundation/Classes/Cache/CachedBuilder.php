<?php

namespace Corals\Foundation\Classes\Cache;

use Corals\Foundation\Traits\Cache\BuilderCaching;
use Corals\Foundation\Traits\Cache\Caching;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * Class CachedBuilder
 * @package Corals\Foundation\Classes\Cache
 */
class CachedBuilder extends EloquentBuilder
{
    use BuilderCaching;
    use Caching;

    /**
     * @param string $column
     * @return mixed
     * @throws \Exception
     */
    public function avg($column)
    {
        if (!$this->isCachable()) {
            return parent::avg($column);
        }

        $cacheKey = $this->makeCacheKey(["*"], null, "-avg_{$column}");

        return $this->cachedValue(func_get_args(), $cacheKey);
    }

    /**
     * @param array $columns
     * @return int|mixed
     * @throws \Exception
     */
    public function count($columns = ["*"])
    {
        if (!$this->isCachable()) {
            return parent::count($columns);
        }

        $cacheKey = $this->makeCacheKey($columns, null, "-count");

        return $this->cachedValue(func_get_args(), $cacheKey);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function delete()
    {
        $this->cache($this->makeCacheTags())
            ->flush();

        return parent::delete();
    }

    /**
     * @param mixed $id
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed|null|static|static[]
     * @throws \Exception
     */
    public function find($id, $columns = ["*"])
    {
        if (!$this->isCachable()) {
            return parent::find($id, $columns);
        }

        $cacheKey = $this->makeCacheKey($columns, null, "-find_{$id}");

        return $this->cachedValue(func_get_args(), $cacheKey);
    }

    /**
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model|mixed|null|object|static
     * @throws \Exception
     */
    public function first($columns = ["*"])
    {
        if (!$this->isCachable()) {
            return parent::first($columns);
        }

        $cacheKey = $this->makeCacheKey($columns, null, "-first");

        return $this->cachedValue(func_get_args(), $cacheKey);
    }

    /**
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|mixed|static[]
     * @throws \Exception
     */
    public function get($columns = ["*"])
    {
        if (!$this->isCachable()) {
            return parent::get($columns);
        }

        $cacheKey = $this->makeCacheKey($columns);

        return $this->cachedValue(func_get_args(), $cacheKey);
    }

    /**
     * @param array $values
     * @return bool
     */
    public function insert(array $values)
    {
        $this->checkCooldownAndFlushAfterPersiting($this->model);

        return parent::insert($values);
    }

    /**
     * @param string $column
     * @return mixed
     * @throws \Exception
     */
    public function max($column)
    {
        if (!$this->isCachable()) {
            return parent::max($column);
        }

        $cacheKey = $this->makeCacheKey(["*"], null, "-max_{$column}");

        return $this->cachedValue(func_get_args(), $cacheKey);
    }

    /**
     * @param string $column
     * @return mixed
     * @throws \Exception
     */
    public function min($column)
    {
        if (!$this->isCachable()) {
            return parent::min($column);
        }

        $cacheKey = $this->makeCacheKey(["*"], null, "-min_{$column}");

        return $this->cachedValue(func_get_args(), $cacheKey);
    }

    /**
     * @param null $perPage
     * @param array $columns
     * @param string $pageName
     * @param null $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|mixed
     * @throws \Exception
     */
    public function paginate(
        $perPage = null,
        $columns = ["*"],
        $pageName = "page",
        $page = null
    )
    {
        if (!$this->isCachable()) {
            return parent::paginate($perPage, $columns, $pageName, $page);
        }

        $page = request("page", $page ?: 1);
        $cacheKey = $this->makeCacheKey($columns, null, "-paginate_by_{$perPage}_{$pageName}_{$page}");

        return $this->cachedValue(func_get_args(), $cacheKey);
    }

    /**
     * @param string $column
     * @param null $key
     * @return \Illuminate\Support\Collection|mixed
     * @throws \Exception
     */
    public function pluck($column, $key = null)
    {
        if (!$this->isCachable()) {
            return parent::pluck($column, $key);
        }

        $keyDifferentiator = "-pluck_{$column}" . ($key ? "_{$key}" : "");
        $cacheKey = $this->makeCacheKey([$column], null, $keyDifferentiator);

        return $this->cachedValue(func_get_args(), $cacheKey);
    }

    /**
     * @param string $column
     * @return mixed
     * @throws \Exception
     */
    public function sum($column)
    {
        if (!$this->isCachable()) {
            return parent::sum($column);
        }

        $cacheKey = $this->makeCacheKey(["*"], null, "-sum_{$column}");

        return $this->cachedValue(func_get_args(), $cacheKey);
    }

    /**
     * @param array $values
     * @return int
     */
    public function update(array $values)
    {
        $this->checkCooldownAndFlushAfterPersiting($this->model);

        return parent::update($values);
    }

    /**
     * @param string $column
     * @return mixed
     * @throws \Exception
     */
    public function value($column)
    {
        if (!$this->isCachable()) {
            return parent::value($column);
        }

        $cacheKey = $this->makeCacheKey(["*"], null, "-value_{$column}");

        return $this->cachedValue(func_get_args(), $cacheKey);
    }

    /**
     * @param array $arguments
     * @param string $cacheKey
     * @return mixed
     * @throws \Exception
     */
    public function cachedValue(array $arguments, string $cacheKey)
    {
        $method = debug_backtrace()[1]['function'];
        $cacheTags = $this->makeCacheTags();
        $hashedCacheKey = sha1($cacheKey);
        $result = $this->retrieveCachedValue(
            $arguments,
            $cacheKey,
            $cacheTags,
            $hashedCacheKey,
            $method
        );

        return $this->preventHashCollision(
            $result,
            $arguments,
            $cacheKey,
            $cacheTags,
            $hashedCacheKey,
            $method
        );
    }

    /**
     * @param array $result
     * @param array $arguments
     * @param string $cacheKey
     * @param array $cacheTags
     * @param string $hashedCacheKey
     * @param string $method
     * @return mixed
     * @throws \Exception
     */
    protected function preventHashCollision(
        array $result,
        array $arguments,
        string $cacheKey,
        array $cacheTags,
        string $hashedCacheKey,
        string $method
    )
    {
        if ($result["key"] === $cacheKey) {
            return $result["value"];
        }

        $this->cache()
            ->tags($cacheTags)
            ->forget($hashedCacheKey);

        return $this->retrieveCachedValue(
            $arguments,
            $cacheKey,
            $cacheTags,
            $hashedCacheKey,
            $method
        );
    }

    /**
     * @param array $arguments
     * @param string $cacheKey
     * @param array $cacheTags
     * @param string $hashedCacheKey
     * @param string $method
     * @return mixed
     * @throws \Exception
     */
    protected function retrieveCachedValue(
        array $arguments,
        string $cacheKey,
        array $cacheTags,
        string $hashedCacheKey,
        string $method
    )
    {
        $this->checkCooldownAndRemoveIfExpired($this->model);

        return $this->cache($cacheTags)
            ->rememberForever(
                $hashedCacheKey,
                function () use ($arguments, $cacheKey, $method) {
                    return [
                        "key" => $cacheKey,
                        "value" => parent::{$method}(...$arguments),
                    ];
                }
            );
    }
}
