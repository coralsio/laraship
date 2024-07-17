<?php

namespace Corals\Foundation\DataTables;

use Corals\Foundation\DataTables\Scopes\SoftDeleteScope;
use Corals\Foundation\Jobs\GenerateExcelForDataTable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation as EloquentRelation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Services\DataTable;

abstract class BaseDataTable extends DataTable
{
    protected $resource_url;

    protected $defaultOrderDirection = 'desc';

    protected $usesQueryBuilderFilters;

    public function __construct()
    {
        $this->usesQueryBuilderFilters = config('corals.query_builder_enabled');
        $this->request = $this->request();

        $filters = array_merge($this->getFilters(), $this->getCustomRenderedFilters());

        if ($this->usesQueryBuilderFilters) {
            $this->addScope(new CoralsQueryBuilderDataTableScope($filters));
        } else {
            $this->addScope(new CoralsScope($filters));
        }

        if ($this->request->boolean('deleted')) {
            $this->addScope(new SoftDeleteScope());
        }

        parent::__construct();
    }

    public function renderAjaxAndActions()
    {
        if ($this->request()->ajax() && $this->request()->wantsJson()) {
            return app()->call([$this, 'ajax']);
        }

        if ($action = $this->request()->get('action') and in_array($action, $this->actions)) {
            if ($action == 'print') {
                return app()->call([$this, 'printPreview']);
            }

            return app()->call([$this, $action]);
        }
    }

    /**
     * @param $resource_url
     * @return $this
     */
    public function setResourceUrl($resource_url)
    {
        $this->resource_url = url($resource_url);
        return $this;
    }

    /**
     * Get DataTables Html Builder instance.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function builder(): Builder
    {
        return app(CoralsBuilder::class);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $language = \Language::getNameEnglish(\App::getLocale());

        $i18nArray = \Cache::remember('datatable_i18n_' . $language, config('corals.cache_ttl'),
            function () use ($language) {
                $languagePath = "assets/corals/plugins/datatables.net/i18n/$language.lang";

                if (file_exists(public_path($languagePath))) {
                    $languagePath = public_path($languagePath);

                    $content = \File::get($languagePath, true);

                    $data = json_decode(cleanJSONFileContent($content), true);

                    return $data;
                } else {
                    return '';
                }
            });

        return $this->builder()
            ->setFilters($this->getFilters())
            ->setCustomRenderedFilters($this->getCustomRenderedFilters())
            ->setBulkActions($this->getBulkActions())
            ->setOptions($this->getOptions())
            ->setTableId($this->getTableId())
            ->setUsesQueryBuilderFilters($this->usesQueryBuilderFilters)
            ->columns($this->getColumns())
            ->setExtraScripts($this->getExtraScripts())
            ->minifiedAjax($this->resource_url ?: url()->current(), $this->getMinifiedAjaxCallback(),
                ['deleted' => $this->request->get('deleted', 0)])
            ->addCheckbox(['datatable_id' => $this->getTableId()], true)
            ->addAction(['width' => '80px'])
            ->parameters(array_merge([
                'language' => $i18nArray,
                'order' => [[0, $this->defaultOrderDirection]],
                "lengthMenu" => [[10, 25, 50, 100, 200, 500, 1000], [10, 25, 50, 100, 200, 500, 1000]],
                'pageLength' => 10,
                "dom" => "Blfrtip",
                'buttons' => ['csv', 'excel', 'print', 'reload'],
                'rowReorder' => [
                    'selector' => 'tr>td:not(:last-child)', // I allow all columns for dragdrop except the last
                    'dataSrc' => 'sortsequence',
                    'update' => false // this is key to prevent DT auto update
                ]
            ], $this->getBuilderParameters()));
    }

    /**
     * @return string
     */
    protected function getMinifiedAjaxCallback(): string
    {
        if ($this->usesQueryBuilderFilters) {
            return 'data.q = jQueryBuilderFilters("#' . $this->getTableId() . '")';
        } else {
            return '$.each(filters("#' . $this->getTableId() . '"), function(name,value){
                        data[name] = value;
                    });';
        }
    }

    /**
     * @return array
     */
    protected function getCustomRenderedFilters(): array
    {
        return [];
    }

    /**
     * @param $download
     * @return ShouldQueue
     */
    protected function getCSVGeneratorJob($download)
    {
        $columns = $this->getExportColumnsFromBuilder();

        return new GenerateExcelForDataTable(
            get_class($this),
            $this->scopes,
            $columns,
            $this->getTableId(),
            user(),
            $download
        );
    }

    /**
     * @param false $download
     * @return mixed|string[]
     */
    public function csv($download = false)
    {
        $csvGenerateHandler = $this->getCSVGeneratorJob($download);

        if ($download) {
            return $csvGenerateHandler->handle();
        }

        dispatch($csvGenerateHandler);

        return [
            "level" => "info",
            "message" => "We will send the Generated File to your E-Mail"
        ];
    }

    public function excel()
    {
        return $this->csv($download = true);
    }

    /**
     * Apply query scopes.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    protected function applyScopes($query): EloquentBuilder|QueryBuilder|EloquentRelation
    {
        $queryClass = strtolower(class_basename($query->getModel()));

        $scopes = \Filters::do_filter('datatable_scopes_' . $queryClass, $this->scopes, $queryClass);

        foreach ($scopes as $scope) {
            $scope->apply($query);
        }

        return $query;
    }

    public function getScopes()
    {
        return $this->scopes;
    }

    public function getFilters()
    {
        return [];
    }

    protected function getColumns()
    {
        return [];
    }


    protected function getBulkActions()
    {
        return [];
    }

    protected function getOptions()
    {
        return [];
    }

    protected function getTableId()
    {
        return class_basename($this);
    }

    protected function getBuilderParameters(): array
    {
        if (!empty($this->getBulkActions())) {
            $idColumnIndex = array_search('id', array_keys($this->getColumns()));

            return ['order' => [[$idColumnIndex + 1, $this->defaultOrderDirection]]];
        }

        return [];
    }

    protected function getExtraScripts()
    {
        return '';
    }

}
