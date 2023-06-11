<?php

namespace Corals\Foundation\Services;

use Corals\Foundation\Transformers\FractalPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Class BaseServiceClass
 * @package Corals\Foundation\Services
 * @method preStore($request, &$additionalData)
 * @method preStoreUpdate($request, &$additionalData)
 * @method postStore($request, &$additionalData)
 * @method postStoreUpdate($request, &$additionalData)
 * @method preUpdate($request, &$additionalData)
 * @method postUpdate($request, &$additionalData)
 * @method preDestroy($request, $model)
 * @method postDestroy($request)
 * @method preRestore($request, $model)
 * @method postRestore($request)
 * @method preHardDelete($request, $model)
 * @method postHardDelete($request)
 */
class BaseServiceClass
{
    protected $model;
    protected $modelClass;
    protected $presenter = null;
    protected $excludedRequestParams = [];

    /**
     * @param $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @param FractalPresenter|null $presenter
     */
    public function setPresenter(FractalPresenter $presenter = null)
    {
        $this->presenter = $presenter;
    }

    public function getRequestData($request)
    {
        if (is_array($request)) {
            return \Arr::except($request, $this->excludedRequestParams);
        } else {
            return $request->except($this->excludedRequestParams);
        }
    }

    public function index($query, $dataTable, $paginated = true)
    {
        foreach ($dataTable->getScopes() as $scope) {
            $scope->apply($query);
        }

        $this->handleOrderBy($query);
        if ($paginated) {
            $perPage = request()->get('limit');

            $result = $query->paginate($perPage);
        } else {
            $result = $query->get();
        }
        if (!is_null($this->presenter)) {
            return $this->presenter->present($result);
        } else {
            return $result;
        }
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    protected function handleOrderBy(Builder $query)
    {
        $orderBy = request()->get('orderBy');

        if (!$orderBy) {
            return $query->orderBy('id', 'desc');
        }

        $direction = intval(request()->get('ascending')) ? 'ASC' : 'DESC';

        if (strpos($orderBy, '.')) {
            list($relation, $column) = explode('.', $orderBy);

            $targetModel = $query->getModel();
            $targetModelTableName = $targetModel->getTable();
            $targetModelPrimaryKey = $targetModel->getKeyName();

            $relationQueryBuilder = $targetModel->{$relation}();

            $relationModelClass = get_class($relationQueryBuilder->getModel());
            $relationForeignKeyName = $relationQueryBuilder->getForeignKeyName();

            $relationTableName = $relationQueryBuilder->getModel()->getTable();

            $query->orderBy(
                $relationModelClass::query()
                    ->select($column)
                    ->whereColumn("$relationTableName.$targetModelPrimaryKey",
                        "$targetModelTableName.$relationForeignKeyName"),
                $direction
            );
        } else {
            $query->orderBy($orderBy, $direction);
        }


        return $query;
    }

    /**
     * @param $request
     * @param $modelClass
     * @param array $additionalData
     * @return mixed
     */
    public function store($request, $modelClass, $additionalData = [])
    {
        if (method_exists($this, 'preStore')) {
            $this->preStore($request, $additionalData);
        }

        if (method_exists($this, 'preStoreUpdate')) {
            $this->preStoreUpdate($request, $additionalData);
        }

        $data = array_merge($this->getRequestData($request), $additionalData);

        $this->model = $modelClass::query()->create($data);

        if (method_exists($this, 'postStore')) {
            $this->postStore($request, $additionalData);
        }

        if (method_exists($this, 'postStoreUpdate')) {
            $this->postStoreUpdate($request, $additionalData);
        }

        return $this->model;
    }

    /**
     * @param $request
     * @param $model
     * @param array $additionalData
     * @return mixed
     */
    public function update($request, $model, $additionalData = [])
    {
        $this->model = $model;

        if (method_exists($this, 'preUpdate')) {
            $this->preUpdate($request, $additionalData);
        }

        if (method_exists($this, 'preStoreUpdate')) {
            $this->preStoreUpdate($request, $additionalData);
        }

        $data = array_merge($this->getRequestData($request), $additionalData);

        $model->update($data);

        $this->model = $model;

        if (method_exists($this, 'postUpdate')) {
            $this->postUpdate($request, $additionalData);
        }

        if (method_exists($this, 'postStoreUpdate')) {
            $this->postStoreUpdate($request, $additionalData);
        }

        return $this->model;
    }

    /**
     * @param $request
     * @param $modelClass
     * @param $config
     * @return \Illuminate\Http\JsonResponse
     */

    public function import(Request $request, $modelClass, $config)
    {
        try {
            $file = $request->file('file_csv');
            $importer = (new BaseImporter($modelClass, $config))->setCsvFile($file);
            $importer->run();
            $importer->finish();
            $message = ['level' => 'success', 'message' => 'File been Created Successfully'];
        } catch (\Exception $exception) {
            log_exception($exception, $modelClass);
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }
        return response()->json($message);
    }

    /**
     * @param $request
     * @param $model
     */
    public function destroy($request, $model)
    {
        if (method_exists($this, 'preDestroy')) {
            $this->preDestroy($request, $model);
        }

        $model->delete();

        if (method_exists($this, 'postDestroy')) {
            $this->postDestroy($request);
        }
    }

    /**
     * @param $request
     * @param $model
     */
    public function restore($request, $model)
    {
        abort_if(user()->cannot('restore', $model), 403);

        if (method_exists($this, 'preRestore')) {
            $this->preRestore($request, $model);
        }

        $model->restore();

        if (method_exists($this, 'postRestore')) {
            $this->postRestore($request);
        }
    }

    /**
     * @param $request
     * @param $model
     */
    public function hardDelete($request, $model)
    {
        abort_if(user()->cannot('hardDelete', $model), 403);

        if (method_exists($this, 'preHardDelete')) {
            $this->preHardDelete($request, $model);
        }

        $model->forceDelete();

        if (method_exists($this, 'postHardDelete')) {
            $this->postHardDelete($request);
        }
    }

    /**
     * @param null $model
     * @param array $extras
     * @return mixed|null
     */
    public function getModelDetails($model = null, $extras = [])
    {
        if (!is_null($model)) {
            $this->setModel($model);
        }

        if (!is_null($this->presenter)) {
            $this->model->setPresenter($this->presenter);
        }

        if (request()->filled('edit')) {
            $extras['edit'] = request()->get('edit');
        }

        if (method_exists($this->model, 'presenter')) {
            return $this->model->presenter($extras);
        }

        return $model;
    }
}
