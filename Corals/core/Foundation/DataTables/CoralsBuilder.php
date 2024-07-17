<?php

namespace Corals\Foundation\DataTables;


use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;

class CoralsBuilder extends Builder
{
    public $filters;
    public $filters_rendered;
    public $options;
    public $bulk_actions;
    public $bulk_actions_rendered;
    public $resource_url;
    public $extra_scripts;
    public $customRenderedFilters;

    public $usesQueryBuilderFiltersFlag;

    public function setFilters($filters = [])
    {
        $this->filters = $filters;
        return $this;
    }

    public function setCustomRenderedFilters($customRenderedFilters)
    {
        $this->customRenderedFilters = $customRenderedFilters;

        return $this;
    }

    public function setUsesQueryBuilderFilters($flag)
    {
        $this->usesQueryBuilderFiltersFlag = $flag;
        return $this;
    }

    /**
     * @return array|false|string
     */
    public function getQueryBuilderFilters(): string
    {
        if (!$this->usesQueryBuilderFilters()) {
            return [];
        }

        $queryBuilderFilters = [];

        $queryBuilderTypesMap = [
            'text' => 'string',
            'select' => 'string',
            'date_range' => 'date',
            'select2' => 'string',
            'select2-ajax' => 'string'
        ];

        $queryBuilderInputType = [
            'select' => 'select',
            'select2' => 'select',
            'boolean' => 'checkbox',
            'text' => 'text'
        ];

        $conditionListsMap = [
            'select2' => 'select',
            'date_range' => 'date',
            'select2-ajax' => 'select'
        ];


        foreach ($this->filters as $key => $filter) {
            $type = data_get($filter, 'type');

            $queryBuilderFilters[] = array_merge($filter, [
                'id' => $key,
                'label' => data_get($filter, 'title'),
                'type' => $queryBuilderTypesMap[$type] ?? $type,
                'input_type' => $type,
                'input' => data_get($queryBuilderInputType, $type),
                'operators' => config("corals.query_builder_condition_types." . data_get($conditionListsMap, $type,
                        $type)),
                'default_value' => data_get($filter, 'default_value'),
                'size' => data_get($filter, 'size'),
            ]);
        }

        return $queryBuilderFilters ? json_encode($queryBuilderFilters) : '';
    }

    /**
     * @return bool
     */
    public function usesQueryBuilderFilters(): bool
    {
        return $this->usesQueryBuilderFiltersFlag;
    }

    public function setOptions($options = [])
    {
        $this->options = $options;
        return $this;
    }

    public function setBulkActions($actions = [])
    {
        $this->bulk_actions = $actions;
        return $this;
    }

    public function setTableId($id): static
    {
        return parent::setTableId($id);
    }

    public function setExtraScripts($scripts)
    {
        $this->extra_scripts = $scripts;
        return $this;
    }

    /**
     * @return string
     * @throws \Exception
     */


    public function bulkActions()
    {
        if (isset($this->bulk_actions_rendered)) {
            return $this->bulk_actions_rendered;
        }
        $this->bulk_actions_rendered = $this->renderBulkActions();
        return $this->bulk_actions_rendered;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function renderBulkActions()
    {
        $bulk_actions = $this->bulk_actions;

        $tableId = $this->getTableAttribute('id');

        if (!$bulk_actions) {
            $this->bulk_actions_rendered = "";
            return $this->bulk_actions_rendered;
        }
        $action_links = "";
        foreach ($bulk_actions as $bulk_action_key => $bulk_action) {

            if (!empty($bulk_action['permission']) && !user()->hasPermissionTo($bulk_action['permission'])) {
                continue;
            }

            if (!empty($bulk_action['policy']) && user()->cannot($bulk_action['policy'], $bulk_action['policy_class'])) {
                continue;
            }

            $action = Arr::get($bulk_action, 'action', $bulk_action_key);
            $href = Arr::get($bulk_action, 'href', $this->resource_url);
            $title = Arr::get($bulk_action, 'modal-title');

            $confirmation = "";

            if ($bulk_action['confirmation']) {
                $confirmation = ' data-confirmation="' . $bulk_action['confirmation'] . '" ';
            }

            $action_links .= '<li><a class="dropdown-item"  href="' . $href . '" ' . $confirmation . ' data-action="' . $action . '" data-title="' . $title . '" >' . $bulk_action['title'] . '</a></li>';
        }

        if (empty($action_links)) {
            return '';
        }

        $actions = ' 
                <div class="btn-group bulk_actions" id="bulk_actions_' . $tableId . '" data-table="' . $tableId . '">
                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">'
            . trans('Corals::labels.actions') .
            '
                  </button>
                  <ul class="dropdown-menu" role="menu">';
        $actions .= $action_links;

        $actions .= ' </ul>
                </div>';


        return $actions;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function filters()
    {
        if (isset($this->filters_rendered)) {
            return $this->filters_rendered;
        }

        $this->filters_rendered = $this->renderFilters();
        return $this->filters_rendered;
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function renderFilters()
    {
        $filtersFields = array_merge($this->filters, $this->customRenderedFilters);

        $tableId = $this->getTableAttribute('id');

        $filters = '<div class="filters" data-table="' . $tableId . '" id="' . $tableId . '_filters">';

        $rowColumns = 0;

        foreach ($filtersFields as $key => $field) {
            if (!$field['active']) {
                continue;
            }

            $field['class'] = $field['class'] ?? 'col-md-3';
            $field['title'] = $field['title'] ?? $key;

            $classArray = explode('-', $field['class']);

            $colNumber = $classArray[count($classArray) - 1];

            if ($rowColumns == 0) {
                $filters .= '<div class="row" >';
            }

            if ($rowColumns > 0 && ($rowColumns + $colNumber) > 12) {
                $rowColumns = 0;
                //row closing
                $filters .= '</div>';
                //start new row
                $filters .= '<div class="row" >';
            }

            $filters .= '<div class="' . $field['class'] . '">';

            $attributes = $field['attributes'] ?? [];

            if (isset($field['id'])) {
                $attributes['id'] = $field['id'];
            }

            $value = request($key, Arr::get($field, 'default_value'));


            if ($renderedFilter = data_get($field, 'html')) {
                $filters .= $renderedFilter;
            } else {
                $attributes['class'] = ($attributes['class'] ?? '') . ' filter';
                $attributes['placeholder'] = $field['placeholder'] ?? $field['title'];
                switch ($field['type']) {
                    case 'text':
                    case 'json':
                        $filters .= \CoralsForm::text($key, null, false, $value, $attributes);
                        break;
                    case 'number':
                        $filters .= \CoralsForm::number($key, null, false, $value, $attributes);
                        break;
                    case 'number_range':
                        $filters .= \CoralsForm::numberRange($key, null, false, $value, $attributes);
                        break;
                    case 'date':
                        $attributes['help_text'] = $field['title'];
                        $filters .= \CoralsForm::date($key, null, false, $value, $attributes);
                        break;
                    case 'date_range':
                        $attributes['help_text'] = $field['title'];
                        $filters .= \CoralsForm::dateRange($key, '', false, $value, $attributes);
                        break;
                    case 'select':
                        $attributes['placeholder'] = trans('Corals::labels.select', ['label' => $field['title']]);
                        $filters .= \CoralsForm::select($key, null, $field['options'], false, $value, $attributes);
                        break;
                    case 'select2':
                        $attributes['data-placeholder'] = trans('Corals::labels.select', ['label' => $field['title']]);

                        $filters .= \CoralsForm::select($key, null, $field['options'] ?? [], false, $value, $attributes,
                            'select2');
                        break;
                    case 'select2-ajax':
                        $filters .= \CoralsForm::select($key, '', [], false, null, [
                            'class' => 'select2-ajax filter',
                            'id' => Arr::get($attributes, 'id'),
                            'placeholder' => 'Select ' . \Arr::get($attributes, 'placeholder'),
                            'data' => array_merge([
                                'model' => $field['model'],
                                'columns' => json_encode($field['columns']),
                                'text_columns' => json_encode($field['text_columns'] ?? $field['columns']),
                                'selected' => json_encode([$value]),
                                'where' => json_encode($field['where'] ?? []),
                            ], $attributes['data'] ?? []),
                        ], 'select2');

                        break;
                    case 'boolean':
                        $filters .= \CoralsForm::checkbox($key, $field['title'],
                            $value == ($field['checked_value'] ?? 1),
                            ($field['checked_value'] ?? 1),
                            ['class' => 'filter']);
                        break;
                }
            }


            //col closing
            $filters .= '</div>';

            if (is_numeric($colNumber)) {
                if (($rowColumns + $colNumber) >= 12) {
                    $rowColumns = 0;
                    //row closing
                    $filters .= '</div>';
                } else {
                    $rowColumns += $colNumber;
                }
            }
        }

        if ($rowColumns + 1 > 12) {
            //row closing
            $filters .= '</div>';
            $rowColumns = 0;
        }
        if ($rowColumns == 0) {
            $filters .= '<div class="row" >';
        }

        if (!empty($filtersFields)) {
            $filters .= '<div class="col-md-2 p-r-0">' .
                \CoralsForm::button('<i class="fa fa-search"></i>',
                    ['class' => 'btn btn-sm btn-primary filterBtn', 'data-table' => $tableId]) . '&nbsp;&nbsp;' .
                \CoralsForm::button('<i class="fa fa-eraser"></i>',
                    ['class' => 'btn btn-sm btn-default clearBtn', 'data-table' => $tableId]);

            $filters .= '</div></div></div>';
        } else {
            $filters = '';
        }

        return $filters;
    }

    /**
     * Add a action column.
     *
     * @param array $attributes
     * @return $this
     */
    public function addAction(array $attributes = [], $prepend = false): static
    {
        $options = $this->options;

        if (isset($options['has_action']) && !$options['has_action']) {
            return $this;
        }

        $attributes = array_merge([
            'defaultContent' => '',
            'data' => 'action',
            'name' => 'action',
            'title' => trans('Corals::labels.action'),
            'render' => null,
            'orderable' => false,
            'searchable' => false,
            'exportable' => false,
            'printable' => true,
            'footer' => '',
        ], $attributes);
        $this->collection->push(new Column($attributes));

        return $this;
    }
    public function addOwner(array $attributes = [], $prepend = false): static
    {
        $options = $this->options;

        if (!isset($options['has_owner']) || !$options['has_owner']) {
            return $this;
        }

        $attributes = array_merge([
            'defaultContent' => '',
            'data' => 'owner',
            'name' => 'owenr',
            'title' => trans('Intelligence::attributes.owner'),
            'render' => null,
            'orderable' => true,
            'searchable' => false,
            'exportable' => false,
            'printable' => true,
            'footer' => '',
        ], $attributes);
        $this->collection->push(new Column($attributes));

        return $this;
    }


    public function assets()
    {
        $options = $this->options;

        static::DataTableScripts();
        if (isset($options['ordering']) && $options['ordering']) {
            \Assets::add(asset('assets/corals/plugins/datatables-reorder/dataTables.rowReorder.min.js'));
            \Assets::add(asset('assets/corals/plugins/datatables-reorder/rowReorder.dataTables.min.css'));
        }
    }

    public static function DataTableScripts()
    {
        \Assets::add(asset('assets/corals/plugins/datatables.net-bs/css/dataTables.bootstrap4.min.css'));
        \Assets::add(asset('assets/corals/plugins/datatables.net/js/jquery.dataTables.min.js'));
        \Assets::add(asset('assets/corals/plugins/datatables.net-bs/js/dataTables.bootstrap4.min.js'));
        \Assets::add(asset('assets/corals/plugins/datatables-buttons/js/dataTables.buttons.min.js'));
        \Assets::add(asset('assets/corals/plugins/datatables-buttons/js/buttons.bootstrap4.min.js'));
        \Assets::add(asset('assets/corals/plugins/datatables-buttons/css/buttons.bootstrap4.min.css'));
        \Assets::add(asset('assets/corals/plugins/datatables.net/js/buttons.server-side.js'));
    }

    /**
     * @param null $script
     * @param array $attributes
     * @return \Illuminate\Support\HtmlString
     * @throws \Exception
     */
    public function scripts($script = null, array $attributes = ['type' => 'text/javascript']): HtmlString
    {
        $tableId = $this->getTableAttribute('id');

        $script = $script ?: $this->generateScripts();

        $options = $this->options;
        if ($this->bulkActions()) {
            $script .= "
            $(document).on('change', '#{$tableId} .datatable-check-all', function(event){
                if($(this).prop('checked')){
                    $('#{$tableId} .datatable-row-checkbox').prop('checked',true);
                }else{
                    $('#{$tableId} .datatable-row-checkbox').prop('checked',false);
                }
                
                if($.fn.iCheck){
                    $('#{$tableId} .datatable-row-checkbox').iCheck('update')
                }
            });
            
            $(document).on('change', '#{$tableId} .datatable-row-checkbox', function(event){
                var checkboxes = $('#{$tableId} .datatable-row-checkbox');
                
                if (checkboxes.length == checkboxes.filter(':checked').length) {
                    $('#{$tableId} .datatable-check-all').prop('checked', 'checked');
                } else {
                    $('#{$tableId} .datatable-check-all').prop('checked', false);
                }
                
                if($.fn.iCheck){
                    $('#{$tableId} .datatable-check-all').iCheck('update')
                }
            });
            
            $(document).on('click', '#bulk_actions_{$tableId} a', function(event){
                event.preventDefault();
                var action = $(this).data('action');
                if(action==='modal-load') return;
                var confirmation_message = $(this).data('confirmation');
				if(confirmation_message){
					themeConfirmation(
						corals.confirmation.title,
						confirmation_message,
						'warning',
						corals.confirmation.yes,
						corals.confirmation.cancel,
						function () {
                            do_bulk_action_{$tableId}(action);
                        }
					)
            
				}else{
                     do_bulk_action_{$tableId}(action);
				}  
			});
         
        function do_bulk_action_{$tableId}(action , data_table ){

            checked_ids = $('#$tableId tbody input:checkbox:checked').map(function () {
                return $(this).val();
            }).get();

        
            $.ajax({
                url: '" . $options['resource_url'] . "/bulk-action',
                type: 'POST',
                data: { selection:  JSON.stringify(checked_ids) , action : action , _token: '" . csrf_token() . "'},
                dataType: 'json',
                success: function (msg) {
                $('#$tableId').DataTable().ajax.reload(); // now refresh datatable
                 themeNotify(msg);
                
                $('#{$tableId} .datatable-check-all').prop('checked',false);
                
                if($.fn.iCheck){
                    $('#{$tableId} .datatable-check-all').iCheck('update')
                }
            }
            });
        } 
            
            ";
        }


        if (isset($options['ordering']) && $options['ordering']) {
            $script .= "$(function (){
                var table = window.LaravelDataTables['{$tableId}'];
                
                table.on('row-reorder', function (e, diff, edit) {
                    var orderArray = [];
                    for (var i = 0, ien = diff.length; i < ien; i++) {
                        var rowData = table.row(diff[i].node).data();
                        orderArray.push({
                            id: rowData.id,			// record id from datatable
                            position: diff[i].newPosition		// new position
                        });
                    }
                    var jsonString = JSON.stringify(orderArray);
                    $.ajax({
                        url: '" . $options['resource_url'] . "/reorder',
                        type: 'POST',
                        data: jsonString,
                        dataType: 'json',
                        success: function (json) {
                        $('#{$tableId}').DataTable().ajax.reload(); // now refresh datatable
                        $.each(json, function (key, msg) {
                            themeNotify(msg);
                        });
                    }
                    });
                });
            });";
        }

        if ($this->usesQueryBuilderFilters()) {
            $filters = $this->getQueryBuilderFilters();
            if (!empty($filters)) {
                $script .= "$(function (){ var options = {filters: " . $filters . "};";

                $script .= sprintf("$('#%s_filters').queryBuilder(options);", $tableId);

                $script .= sprintf("$('.reset-btn').on('click',function(){
                            var tableId = $(this).data('table');
                            $('#%s_filters').queryBuilder('reset');
                            window.LaravelDataTables[tableId].draw(); });",
                    $tableId);

                $script .= sprintf(" var url = new URL(window.location.href);
                                var arr = {};
                            parse_str(url.searchParams.toString(), arr);
                            if(arr.q){
                               $('#%s_filters').queryBuilder('setRules', Object.assign({}, arr.q));
                               $('#%s_filtersCollapse .filterBtn').click();
                            }});", $tableId, $tableId);
            }
        }
        if (isset($options['filter_auto_open']) && $options['filter_auto_open']) {
            $script .= sprintf("
                                $('#%s_filtersCollapse').collapse('show');
                        ", $tableId);
        }

        if (!empty($this->extra_scripts)) {
            $script .= $this->extra_scripts;
        }

        return parent::scripts($script, $attributes);
    }

    /**
     * Add a checkbox column.
     * @param array $attributes
     * @param bool $position
     * @return $this|Builder
     * @throws \Exception
     */
    public function addCheckbox(array $attributes = [], $position = false): static
    {
        if (!$this->bulkActions()) {
            return $this;
        }
        $dataTableId = \Arr::pull($attributes, 'datatable_id');

        $attributes = array_merge([
            'defaultContent' => '',
            'title' => '<div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="datatable-check-all custom-control-input" id="' . $dataTableId . '_dataTablesCheckbox' . '"/><label class="custom-control-label" for="' . $dataTableId . '_dataTablesCheckbox"> </label>
                                        </div>',
            'data' => 'checkbox',
            'name' => 'checkbox',
            'orderable' => false,
            'searchable' => false,
            'exportable' => false,
            'printable' => true,
            'width' => '10px',
        ], $attributes);
        $column = new Column($attributes);

        if ($position === true) {
            $this->collection->prepend($column);
        } elseif ($position === false || $position >= $this->collection->count()) {
            $this->collection->push($column);
        } else {
            $this->collection->splice($position, 0, [$column]);
        }

        return $this;
    }
}
