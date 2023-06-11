<?php

namespace Corals\Foundation\Transformers;

use Corals\Foundation\Contracts\PresenterInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\AbstractPaginator;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\SerializerAbstract;

/**
 * Class FractalPresenter
 * @package Corals\Foundation\Transformers
 */
abstract class FractalPresenter implements PresenterInterface
{
    /**
     * @var string
     */
    protected $resourceKeyItem = null;

    /**
     * @var string
     */
    protected $resourceKeyCollection = null;

    /**
     * @var \League\Fractal\Manager
     */
    protected $fractal = null;

    /**
     * @var \League\Fractal\Resource\Collection
     */
    protected $resource = null;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (!class_exists('League\Fractal\Manager')) {
            throw new Exception(trans('league fractal package is required'));
        }

        $this->fractal = new Manager();
        $this->parseIncludes();
        $this->setupSerializer();
    }

    /**
     * @return $this
     */
    protected function setupSerializer()
    {
        $serializer = $this->serializer();

        if ($serializer instanceof SerializerAbstract) {
            $this->fractal->setSerializer(new $serializer());
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function parseIncludes()
    {
        $request = app('Illuminate\Http\Request');

        $paramIncludes = 'include';

        if ($request->has($paramIncludes)) {
            $this->fractal->parseIncludes($request->get($paramIncludes));
        }

        return $this;
    }

    /**
     * Get Serializer
     *
     * @return SerializerAbstract
     */
    public function serializer()
    {
        return new DataArraySerializer();
    }

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    abstract public function getTransformer();

    /**
     * @param $data
     * @param array $extras
     * @return array|mixed
     * @throws Exception
     */
    public function present($data, $extras = [])
    {
        if (!class_exists('League\Fractal\Manager')) {
            throw new Exception(trans('league fractal package is required'));
        }


        if ($data instanceof EloquentCollection) {
            $this->resource = $this->transformCollection($data, $extras);
        } elseif ($data instanceof AbstractPaginator) {
            $this->resource = $this->transformPaginator($data, $extras);
        } else {
            $this->resource = $this->transformItem($data, $extras);
        }

        return $this->fractal->createData($this->resource)->toArray();
    }

    /**
     * @param $data
     * @param array $extras
     * @return Item
     */
    protected function transformItem($data, $extras = [])
    {
        return new Item($data, $this->getTransformer($extras), $this->resourceKeyItem);
    }

    /**
     * @param $data
     * @param array $extras
     * @return Collection
     */
    protected function transformCollection($data, $extras = [])
    {
        return new Collection($data, $this->getTransformer($extras), $this->resourceKeyCollection);
    }

    /**
     * @param $paginator
     * @param array $extras
     * @return Collection
     */
    protected function transformPaginator($paginator, $extras = [])
    {
        $collection = $paginator->getCollection();
        $resource = new Collection($collection, $this->getTransformer($extras), $this->resourceKeyCollection);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return $resource;
    }
}
