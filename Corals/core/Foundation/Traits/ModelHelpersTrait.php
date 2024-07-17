<?php

namespace Corals\Foundation\Traits;


use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait ModelHelpersTrait
{
    public $related = null;

    public $htmlentitiesExcluded = [];

    protected function getModelId($id)
    {
        if (method_exists($this, 'findByHash')) {
            if (is_null($id)) {
                $id = $this->hashed_id;
            } elseif (is_null(hashids_decode($id))) {
                $id = hashids_encode($id);
            }
        } else {
            if (is_null($id)) {
                $id = $this->id;
            }
        }

        return $id;
    }

    public function getShowURL($id = null, $params = [])
    {
        $id = $this->getModelId($id);

        $config = config($this->config);

        if ($config) {
            if (!isset($config['resource_url'])) {
                $url = $this->getResourceRouteFor('show', $id);
            } else {
                $url = $this->getUrlFor('show', $id);
            }

            return urlWithParameters($url, $params);
        } else {
            return null;
        }
    }

    private function getUrlFor($action, $id = null)
    {
        $config = config($this->config);

        if ($action == 'show') {
            $url = $config['resource_url'] . '/' . $this->getModelId($id);
        } elseif ($action == 'edit') {
            $url = $config['resource_url'] . '/' . $this->getModelId($id) . '/edit';
        } elseif ($action == 'create') {
            $url = $config['resource_url'] . '/create';
        }

        return $url;
    }

    protected function getResourceRouteFor($action, $id = null, $extras = [])
    {
        $config = config($this->config);

        if (!isset($config['resource_relation']) || !isset($config['resource_route'])) {
            return null;
        }

        $resourceRelation = $config['resource_relation'];

        if ($this->related) {
            $related = $this->related;
        } else {
            $related = request()->route($resourceRelation);
        }

        if (!$related) {
            return null;
        }

        $route = Str::replaceLast('index', $action, $config['resource_route']);


        if ($action == 'create') {
            $url = route($route, [$resourceRelation => $related->hashed_id]);
        } else {
            $url = route($route,
                [$resourceRelation => $related->hashed_id, $config['relation'] => $id]);
        }

        return $url;
    }

    public function getEditUrl($resource_url = null, $id = null, $params = [])
    {
        $id = $this->getModelId($id);

        if (!$resource_url) {
            $config = config($this->config);

            if (!isset($config['resource_url'])) {
                $url = $this->getResourceRouteFor('edit', $id);
            } else {
                $url = $this->getUrlFor('edit', $id);
            }

            return urlWithParameters($url, $params);
        }

        return urlWithParameters($resource_url . '/' . $id . '/edit', $params);
    }

    public static function getCreateUrl($params = [])
    {
        $obj = new static();

        $config = config($obj->config);

        if ($config) {
            if (!isset($config['resource_url'])) {
                $url = $obj->getResourceRouteFor('create');
            } else {
                $url = $obj->getUrlFor('create');
            }

            return urlWithParameters($url, $params);
        } else {
            return null;
        }
    }

    public function getIdentifier($key = null)
    {
        if (!is_null($key)) {
            $identifier = $this->attributes[$key] ?? '-';
        } elseif (Arr::has($this->attributes, 'name')) {
            $identifier = $this->name;
        } elseif (Arr::has($this->attributes, 'title')) {
            $identifier = $this->title;
        } elseif (Arr::has($this->attributes, 'caption')) {
            $identifier = $this->caption;
        } elseif (Arr::has($this->attributes, 'label')) {
            $identifier = $this->label;
        } elseif (Arr::has($this->attributes, 'code')) {
            $identifier = $this->code;
        } else {
            $identifier = $this->getKey();
        }

        return $identifier;
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function getConfig($key, $default = null)
    {
        return config($this->config . '.' . $key, $default);
    }

    public static function getConfigStatic($key, $default = null)
    {
        return with(new static)->getConfig($key, $default);
    }


    /**
     * @param null $key
     * @return array|bool
     */
    public static function htmlentitiesExcluded($key = null)
    {
        $excluded = ['content', 'description', 'body'];

        if (property_exists(get_called_class(), 'htmlentitiesExcluded')) {
            $excluded = array_merge($excluded, with(new static())->htmlentitiesExcluded);
        }

        if ($key) {
            return in_array($key, $excluded);
        }

        return $excluded;
    }
}
