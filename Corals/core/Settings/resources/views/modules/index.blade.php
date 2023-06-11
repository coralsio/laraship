@extends('layouts.master')

@section('title',$title)

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('modules') }}
        @endslot
    @endcomponent
@endsection

@section('actions')
    {!! CoralsForm::link(url($resource_url.'/rescan'),'Settings::labels.module.rescan',['class'=>'btn btn-warning']) !!}
    {!! CoralsForm::link(url($resource_url.'/add'),'Settings::labels.module.add_new',['class'=>'btn btn-primary modal-load','data-title'=>"Add New Module"]) !!}
    {!! CoralsForm::link(url($resource_url.'?check-for-updates=true'),'Settings::labels.module.check_update',['class'=>'btn btn-success']) !!}
@endsection

@section('css')
    <style>
        .table-hover > tbody > tr:hover {
            background-color: #ccc;
            color: black;
        }

        .table-hover > tbody > tr:hover .text-primary {
            color: black;
        }
    </style>
@endsection

@section('content')
    @component('components.box')
        @slot('box_title')
            @lang('Module::labels.module.box_title')
        @endslot
        @if($has_updates)
            <div id="update_notification" class="alert alert-info">
                <button type="button" style="margin-left: 20px" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                @lang('Settings::labels.module.update_available')
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table color-table info-table table table-hover table-striped table-condensed">
                        <thead>
                        <tr>
                            <th style="min-width: 250px;">@lang('Settings::labels.module.name')</th>
                            <th>@lang('Settings::labels.module.description')</th>
                            <th>@lang('Settings::labels.module.notes')</th>
                            <th>@lang('Settings::labels.module.version')</th>
                            <th>@lang('Settings::labels.module.install_version')</th>
                            <th>@lang('Settings::labels.module.type')</th>
                            <th>@lang('Settings::labels.module.author')</th>
                            <th>@lang('Settings::labels.module.code')</th>
                            <th>@lang('Settings::labels.module.enabled')</th>
                            <th>@lang('Corals::labels.action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($modules as $module)
                            <tr id="{{ $module->code }}" data-hashedid="{{ $module->hashed_id }}">
                                <td class="module_name">
                                    <b class="text-primary">
                                        {!! property_exists($module,'icon')?'<i class="'.$module->icon.' m-r-5"></i>':'' !!}
                                        {{ $module->name }}
                                    </b>
                                </td>
                                <td>{!! generatePopover($module->description)  !!}</td>
                                <td>{!! generatePopover($module->notes,'', 'fa fa-sticky-note text-danger')  !!}</td>
                                <td><b>{{ $module->version }}</b></td>
                                <td><b>{{ $module->installed_version??'-' }}</b></td>
                                <td>{!! $module->type_formatted !!}</td>
                                <td>{{ $module->author }}</td>
                                <td>{{ $module->code }}</td>
                                <td>{!! $module->enabled?'<i class="fa fa-check-circle text-success"></i>':'<i class="fa fa-ban text-danger"></i>' !!}</td>
                                <td class="actions">{!! \Modules::getModuleAction($module,$remote_updates)  !!}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endcomponent
@endsection

@section('js')

@endsection