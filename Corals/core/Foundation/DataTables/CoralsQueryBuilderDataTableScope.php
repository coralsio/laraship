<?php


namespace Corals\Foundation\DataTables;


use Corals\Foundation\DataTables\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\Contracts\DataTableScope;

class CoralsQueryBuilderDataTableScope implements DataTableScope
{

    protected $filters;

    /**
     * CoralsQueryBuilderDataTableScope constructor.
     * @param $filters
     */
    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function apply($query)
    {
        if ($queryBuilderJson = request('q')) {
            $queryBuilderParser = new QueryBuilderParser($this->filters);
            $query = $queryBuilderParser->parse(json_encode($queryBuilderJson), $query);
        }

        return $query;
    }
}
