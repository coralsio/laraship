<?php


namespace Corals\Foundation\DataTables;


use Corals\Foundation\DataTables\QueryBuilderParser\QueryBuilderParser;
use Yajra\DataTables\Contracts\DataTableScope;

class CoralsQueryBuilderDataTableScope implements DataTableScope
{
    /**
     * CoralsQueryBuilderDataTableScope constructor.
     * @param $filters
     * @param $urlQuery
     */
    public function __construct(protected $filters, protected $urlQuery)
    {
    }

    public function apply($query)
    {
        if ($queryBuilderJson = $this->urlQuery) {
            $queryBuilderParser = new QueryBuilderParser($this->filters);
            $query = $queryBuilderParser->parse(json_encode($queryBuilderJson), $query);
        }

        return $query;
    }
}
