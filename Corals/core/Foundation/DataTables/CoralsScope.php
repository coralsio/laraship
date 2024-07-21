<?php

namespace Corals\Foundation\DataTables;


use Carbon\Carbon;
use Illuminate\Support\Arr;
use Yajra\DataTables\Contracts\DataTableScope;

class CoralsScope implements DataTableScope
{
    public $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function apply($query)
    {
        $filters = $this->filters;

        if (empty($filters)) {
            return $query;
        }

        $requestFilters = request()->get('filters');

        if (!is_array($requestFilters)) {
            $requestFilters = urldecode($requestFilters);
            $requestFilters = get_request_filters_array($requestFilters);
        }

        $requestFilters = array_merge(request()->only(array_keys($filters)), $requestFilters);

        if (empty($requestFilters)) {
            return $query;
        }

        $baseTable = $query->getModel()->getTable();

        foreach ($requestFilters as $column => $value) {
            $filter = Arr::get($filters, $column, Arr::get($filters, $column . "[]"));

            if (empty($filter) || !($value || $value === 0 || $value === 0.0 || $value === false)) {
                continue;
            }

            if (\Arr::get($filter, 'ignore_query_scope', false)) {
                continue;
            }

            $builder = data_get($filter, 'builder');

            if (class_exists($builder)) {
                app($builder)->apply($query, $column, $value);
                continue;
            }

            $condition = $filter['condition'] ?? '=';

            $relation = null;

            $column = Arr::get($filter, 'column', $column);

            if (stripos($column, '.') != false) {
                list($relation, $column) = explode('.', $column);
            }


            if ($isJson = data_get($filter, 'is_json')) {
                $function = 'json';
                $jsonColumn = $filter['json_column'];

                $condition = \DB::raw(sprintf("LOWER(json_extract(`%s`,'$.%s')) like LOWER('%%$value%%')", $jsonColumn, $column));

            } else {
                switch ($filter['type'] ?? null) {
                    case 'date':
                        $function = $this->functionMap('date');
                        break;
                    case 'date_range':
                    case 'pre_defined_date':
                        $function = $this->functionMap($filter['type']);

                        if (is_array($value) && count($value) == 1) {
                            if (isset($value['from'])) {
                                $function = 'whereDate';
                                $value = $value['from'];
                                $condition = '>=';
                            } elseif (isset($value['to'])) {
                                $function = 'whereDate';
                                $value = $value['to'];
                                $condition = '<=';
                            } else {
                                $function = 'where';
                                $value = current($value);
                            }
                        } else {
                            $value['from'] = Carbon::parse($value['from'])->startOfDay()->toDateTimeString();
                            $value['to'] = Carbon::parse($value['to'])->endOfDay()->toDateTimeString();
                        }
                        break;
                    case 'number_range':
                        $function = $this->functionMap('between');

                        if (is_array($value) && count($value) == 1) {
                            $function = 'where';
                            if (isset($value['from'])) {
                                $value = floatval($value['from']);
                                $condition = '>=';
                            } elseif (isset($value['to'])) {
                                $value = floatval($value['to']);
                                $condition = '<=';
                            } else {
                                $value = floatval(current($value));
                            }
                        }
                        break;
                    default:
                        $function = isset($filter['function']) ? $this->functionMap($filter['function']) : 'where';
                }
            }

            switch ($condition) {
                case 'like':
                    $value = "%$value%";
                    break;
            }


            if (Arr::get($filter, 'is_morph', false)) {
                $morphTypes = $filter['morph_types'];
                $this->buildMorphQuery($query, $relation, $morphTypes, $relation, $function, $column, $condition,
                    $value);
            } else {
                $this->buildQuery($relation, $query, $function, $condition, $column, $value, $baseTable);
            }
        }

        return $query;
    }

    /**
     * @param $relation
     * @param $query
     * @param $function
     * @param $condition
     * @param $column
     * @param $value
     * @param $baseTable
     */
    public function buildQuery($relation, $query, $function, $condition, $column, $value, $baseTable): void
    {
        if ($relation && method_exists($query->getModel(), $relation)) {
            $query->whereHas($relation,
                function ($relQuery) use ($function, $condition, $column, $value, $relation) {
                    $relationBaseTable = $relQuery->getModel()->getTable();
                    $this->query($relQuery, $function, $relationBaseTable, $column, $condition, $value);
                });
        } elseif ($relation) {
            $this->query($query, $function, $relation, $column, $condition, $value);
        } else {
            $this->query($query, $function, $baseTable, $column, $condition, $value);
        }
    }

    /**
     * @param $query
     * @param $function
     * @param $baseTable
     * @param $column
     * @param $condition
     * @param $value
     */
    protected function query($query, $function, $baseTable, $column, $condition, $value): void
    {
        if (in_array($function, ['whereBetween', 'whereNotBetween', 'whereIn', 'whereNotIn'])) {
            $query->{$function}("$baseTable.$column", $value);
        } elseif ($function == 'json') {
            $query->whereRaw($condition);
        } elseif (in_array($function, ['whereNull', 'whereNotNull'])) {
            $query->{$function}("$baseTable.$column");
        } else {
            $query->{$function}("$baseTable.$column", $condition, $value);
        }
    }

    protected function buildMorphQuery(
        $query,
        $morphColumn,
        $morphTypes,
        $relation,
        $function,
        $column,
        $condition,
        $value
    ): void
    {
        $query->whereHasMorph($morphColumn, $morphTypes,
            function ($query) use ($relation, $function, $column, $condition, $value) {
                $relationBaseTable = $query->getModel()->getTable();
                $this->query($query, $function, $relationBaseTable, $column, $condition, $value);
            });
    }


    private function functionMap($function = '')
    {
        $functionMap = [
            'between' => 'whereBetween',
            'not between' => 'whereNotBetween',
            'in' => 'whereIn',
            'not in' => 'whereNotIn',
            'null' => 'whereNull',
            'not null' => 'whereNotNull',
            'date' => 'whereDate',
            'date_range' => 'whereBetween',
            'pre_defined_date' => 'whereBetween'
        ];

        $function = $functionMap[$function] ?? 'where';
        return $function;
    }
}
