<?php

namespace Corals\Settings\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Foundation\Search\Search;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UtilitiesController extends BaseController
{
    public function __construct()
    {
        $this->corals_middleware_except = ['select2Public'];
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * {!! CoralsForm::select('users','Users', [], false, null,
     * ['class'=>'select2-ajax','data'=>[
     * 'model'=>\Corals\User\Models\User::class,
     * 'columns'=> json_encode(['name','email']),
     * 'selected'=>json_encode([1=>'zzzzzz',3=>'xxxxxxxxx']),
     * 'orWhere'=>json_encode([]),
     * 'where'=>json_encode([
     * ['field'=>'tableX.col_x','operation'=>'=','value'=>'xx'],
     * ['field'=>'tableX.col_y','operation'=>'=','value'=>'yy']
     * ]),
     * 'join' =>[
     * 'table'=>'tableX',
     * 'type'=>'leftJoin',
     * 'on' =>['tableX.user_id','users.id']
     * ]
     * ]],'select2')
     * !!}
     */
    public function select2(Request $request)
    {
        if (!$request->get('model')) {
            return response()->json([]);
        }

        $query = strip_tags($request->get('query'));
        $columns = $request->get('columns', []);
        $keyColumn = $request->get('key_column', 'id');
        $textColumns = $request->get('textColumns', []);
        $model = $request->get('model');
        $selected = $request->get('selected', []);
        $where = $request->get('where', []);
        $scopes = $request->get('scopes', []);
        $scopesParams = $request->get('scope_params', []);
        $orWhere = $request->get('orWhere', []);
        $resultMapper = $request->get('resultMapper', []);
        $join = $request->get('join', []);
        $showIdentifier = $request->get('show_identifier', false);


        $model = Relation::$morphMap[$model] ?? $model;

        $modelObject = with(new $model);

        abort_if(!user() && !$modelObject->allowPublicSearch, 401, 'Unauthorized');

        $model_table = $modelObject->getTable();

        $result = null;

        if (empty($query) && empty($selected)) {
            return response()->json([]);
        }

        if (is_string($columns)) {
            $columns = json_decode($columns, true);
        }

        if (is_string($where)) {
            $where = json_decode($where, true);
        }


        //check if table name is set or not
        $columns = array_map(function ($column) use ($model_table) {
            $column = strpos($column, '.') !== false ? $column : ($model_table . '.' . $column);
            return $column;
        }, $columns);

        if (empty($textColumns)) {
            $textColumns = $columns;
        } else {
            $textColumns = array_map(function ($column) use ($model_table) {
                $column = strpos($column, '.') !== false ? $column : ($model_table . '.' . $column);
                return $column;
            }, $textColumns);
        }
        if (empty($columns)) {
            $search = new Search();
            $result = $model::query();
            $search->AddSearchPart($result, $query, $model);
            $showIdentifier = true;
        } else {
            $result = $model::where(function ($q) use ($columns, $query, $model_table) {
                foreach ($columns as $index => $column) {
                    if (!empty($query)) {
                        $q = $q->orWhere("$column", 'like', '%' . $query . '%');
                    }
                }
            });
        }
        if (!empty($selected)) {
            $result = $result->whereIn($model_table . ".$keyColumn", Arr::wrap($selected));
        }

        if (!empty($where)) {
            $result = $this->applyConditions($where, $result);
        }

        if (!empty($orWhere)) {
            $result = $result->where(function ($query) use ($orWhere) {
                return $query = $this->applyConditions($orWhere, $query, 'orWhere');
            });
        }


        if (!empty($join)) {
            $result = $result->{$join['type']}($join['table'], $join['on'][0], $join['on'][1]);
        }

        $queryClass = strtolower(class_basename($model));

        $scopes = \Filters::do_filter('select_scopes_' . $queryClass, $scopes, $queryClass);

        foreach ($scopes as $scope) {
            if (is_string($scope)) {
                $scope = new $scope;
            }

            $scope->apply($result, $scopesParams);
        }


        $sep = "";
        $text = "";
        foreach ($textColumns as $textColumn) {
            $text .= $sep . $textColumn;
            $sep = ',';
        }

        $id = $model_table . ".$keyColumn as key";

        if ($showIdentifier) {
            $result->select("$model_table.*");
        } else {
            $result->select(\DB::raw("CONCAT_WS(' - ', $text) as text"), $id);
        }

        $result = $result->limit(200)->distinct()->get();

        $results = [];

        foreach ($result as $item) {
            array_push($results, [
                'id' => $item->key,
                'text' => $showIdentifier ? strip_tags($item->present('identifier')) : $item->text
            ]);
        }

        if (!empty($resultMapper)) {
            $results = call_user_func($resultMapper, $results);
        }

        return response()->json($results);
    }

    protected function applyConditions($conditions, $query, $where = 'where')
    {
        foreach ($conditions as $w) {
            switch ($w['operation']) {
                case 'in':
                    $query = $query->{$where . 'In'}($w['field'], $w['value']);
                    break;
                case 'not_in':
                    $query = $query->{$where . 'NotIn'}($w['field'], $w['value']);
                    break;
                case 'is_null':
                    $query = $query->{$where . 'Null'}($w['field']);
                    break;
                case 'not_null':
                    $query = $query->{$where . 'NotNull'}($w['field']);
                    break;
                default:
                    $query = $query->{$where}($w['field'], $w['operation'], $w['value']);
            }
        }

        return $query;
    }

    public function renderQueryBuilderFilterInput(Request $request)
    {
        $filter = $request->get('filter');

        $key = data_get($filter, 'name');
        $value = data_get($filter, 'default_value');

        $attributes = [];

        $operator = $request->get('operator');

        if (in_array(data_get($operator, 'type'), ['in', 'not_in'])) {
            $attributes['multiple'] = true;
            $key .= '[]';
        }


        $type = data_get($filter, 'input_type');

        switch ($type) {
            case 'text':
            case 'json':
                return \CoralsForm::text($key, null, false, $value, $attributes);
                break;
            case 'number':
                return \CoralsForm::number($key, null, false, $value, $attributes);
                break;
            case 'number_range':
                return \CoralsForm::numberRange($key, null, false, $value, $attributes);
                break;
            case 'date':
                $attributes['help_text'] = $filter['label'];
                return \CoralsForm::date($key, null, false, $value, $attributes);
                break;
            case 'date_range':
            case 'pre_defined_date':
                return \CoralsForm::date($key, '', false, $value, $attributes);
                break;
            case 'select':
                $attributes['placeholder'] = trans('Corals::labels.select', ['label' => $filter['label']]);
                return \CoralsForm::select($key, null, data_get($filter, 'options', []), false, $value, $attributes);
                break;
            case 'select2':

                $attributes['data-placeholder'] = trans('Corals::labels.select', ['label' => $filter['label']]);

                return \CoralsForm::select($key, null, data_get($filter, 'options', []), false, $value, $attributes,
                    'select2');
                break;
            case 'select2-ajax':
                return \CoralsForm::select($key, '', [], false, null, [
                    'class' => 'select2-ajax',
                    'multiple' => Arr::get($attributes, 'multiple', false),
                    'id' => Arr::get($attributes, 'id'),
                    'placeholder' => 'Select ' . \Arr::get($attributes, 'placeholder'),
                    'data' => array_merge([
                        'model' => $filter['model'],
                        'columns' => json_encode($filter['columns'] ?? []),
                        'text_columns' => json_encode($filter['text_columns'] ?? $filter['columns'] ?? []),
                        'selected' => json_encode([$value]),
                        'where' => json_encode($filter['where'] ?? []),
                    ], $attributes['data'] ?? []),
                ], 'select2');

                break;
            case 'boolean':
                return \CoralsForm::checkbox($key, $filter['label'], $value == ($filter['checked_value'] ?? 1),
                    ($filter['checked_value'] ?? 1),
                    ['class' => 'filter']);
                break;
        }
    }

    public function select2Public(Request $request)
    {
        return $this->select2($request);
    }
}
