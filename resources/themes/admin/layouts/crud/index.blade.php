@extends('layouts.master')

@section('css')
    <style type="text/css">
        .table > thead > tr > th,
        .table > tbody > tr > th,
        .table > tfoot > tr > th,
        .table > thead > tr > td,
        .table > tbody > tr > td,
        .table > tfoot > tr > td {
            vertical-align: middle;
        }

        .dropdown-menu {
            left: -180px;
        }
    </style>
@endsection

@section('title', $title)

@section('actions')
    @if(!empty($dataTable->bulkActions()))
        {!! $dataTable->bulkActions() !!}
    @endif

    @if(!empty($dataTable->filters()))
        {!! CoralsForm::link('#'.$dataTable->getTableAttributes()['id'].'_filtersCollapse',trans('Corals::labels.filter_open'),['class'=>'btn btn-info','data'=>['toggle'=>"collapse"]]) !!}
    @endif
    @isset($resourceModel)
        {!! $resourceModel->getGenericActions() !!}
    @endisset
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @component('components.box',['box_class'=>'box-primary'])

                @if(method_exists($dataTable, 'usesQueryBuilderFilters') && $dataTable->usesQueryBuilderFilters())

                    <div id="{{ $dataTable->getTableAttributes()['id'] }}_filtersCollapse"
                         class="filtersCollapse collapse">
                        <div id="{{$dataTable->getTableAttributes()['id']}}_filters"
                             data-table="{{$dataTable->getTableAttributes()['id']}}"></div>
                        <button class="btn btn-primary filterBtn"
                                data-table="{{$dataTable->getTableAttributes()['id']}}">
                            <i class="fa fa-search"></i>
                        </button>
                        <button class="btn btn-default reset-btn"
                                data-table="{{$dataTable->getTableAttributes()['id']}}">
                            <i class="fa fa-eraser"></i>
                        </button>
                    </div>
                @elseif(!empty($dataTable->filters()))
                    <div id="{{ $dataTable->getTableAttributes()['id'] }}_filtersCollapse"
                         class="filtersCollapse collapse">
                        <br/>
                        {!! $dataTable->filters() !!}
                    </div>
                @endif
                <div class="table-responsive m-t-10" style="min-height: 350px;padding-bottom: 20px;">
                    {!! $dataTable->table(['class' => 'table table-hover table-striped table-condensed dataTableBuilder','style'=>'width:100%;']) !!}
                </div>
            @endcomponent
        </div>
    </div>
@endsection

@section('js')

    {!! $dataTable->assets() !!}
    {!! $dataTable->scripts() !!}
@endsection
