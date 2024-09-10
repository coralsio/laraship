<?php

namespace Corals\Foundation\DataTables;


use Carbon\Carbon;
use Illuminate\Support\Arr;
use Yajra\DataTables\Contracts\DataTableScope;

class CoralsScope implements DataTableScope
{
    /**
     * CoralsScope constructor.
     * @param $filters
     * @param $requestFilter
     * @param array $urlFilters
     * @param array $dataTable
     */
    public function __construct(
        protected $filters,
        protected $requestFilter,
        protected $urlFilters = [],
        protected array $dataTable = []
    )
    {
    }

    public function apply($query)
    {
        $ajaxRequestFilters = urldecode($this->requestFilter);

        $filters = $this->filters;


        $filtersRequest = array_merge(
            \Arr::dot($this->urlFilters),
            getRequestFiltersArray($ajaxRequestFilters)
        );

        if ($this->dataTable) {
            $dt = app(
                data_get($this->dataTable, 'class'),
                data_get($this->dataTable, 'parameters'),
            );

            if (method_exists($dt, 'applyDefaultFilters')) {
                $dt->applyDefaultFilters($query, $filtersRequest, $this->urlFilters);
            }
        }

        if (!$ajaxRequestFilters || empty($filters)) {
            return $query;
        }

        request()->request->add($filtersRequest);

        $baseTable = $query->getModel()->getTable();


        foreach ($filtersRequest as $column => $value) {
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

            if ($function == 'whereIn' && is_string($value)) {
                $value = explode(',', $value);
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
