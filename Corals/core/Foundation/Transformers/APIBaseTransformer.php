<?php

namespace Corals\Foundation\Transformers;

use League\Fractal\TransformerAbstract;

class APIBaseTransformer extends TransformerAbstract
{
    protected $editModeEnabled = false;

    public function __construct($extras = [])
    {
        $this->editModeEnabled = data_get($extras, 'edit') == 1;
    }

    protected function isInEditMode()
    {
        return $this->editModeEnabled;
    }

    /**
     * @param array $transformedArray
     * @param null $model
     * @param array $extra
     * @return array
     */
    public function transformResponse(array $transformedArray, $model = null, $extra = [])
    {
        $requestOnly = request()->get('select');

        if (!empty($requestOnly)) {
            $onlyColumns = explode(',', $requestOnly);

            $transformedArray = array_filter($transformedArray, function ($key) use ($onlyColumns) {
                return in_array($key, $onlyColumns);
            }, ARRAY_FILTER_USE_KEY);
        }

        if (!$this->isInEditMode()) {
            $transformedArray = array_map(function ($value) {
                return $value ?? '-';
            }, $transformedArray);
        }

        return array_merge($this->actionPermission($model), $transformedArray);
    }

    /**
     * @param $model
     * @return array
     */
    protected function actionPermission($model)
    {
        if (!$model || !user()) {
            return [];
        }

        return [
            'actions' => array_map(function ($action) {
                if (data_get($action, 'data.action')) {
                    $action['method'] = data_get($action, 'data.action');
                }

                if (data_get($action, 'href')) {
                    $action['endpoint'] = str_replace(url('/'), '', data_get($action, 'href'));
                }

                $keysToClean = ['icon', 'target', 'policies', 'class', 'permissions', 'data', 'href'];

                foreach ($keysToClean as $key) {
                    unset($action[$key]);
                }
                return $action;
            }, $model->getActions(true))
        ];
    }
}
