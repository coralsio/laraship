@extends('layouts.master')

@section('title', $title)

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('settings') }}
        @endslot
    @endcomponent
@endsection

@section('actions')
    @include('components.button_group', ['class'=>'btn btn-success','actions'=>$actions,'label'=>trans('Corals::labels.create')])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @component('components.box')
                <ul id="tabs" class="nav nav-tabs">
                    @foreach($settingsCategorized as $category => $settings)
                        <li class="nav-item {{ $loop->first? 'active' : '' }}">
                            <a href="#{{\Str::slug($category)}}" aria-expanded="true"
                               class="{{ $loop->first ? 'active':'' }} nav-link"
                               data-toggle="tab">{{ $category }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach($settingsCategorized as $category => $settings)
                        <div class="tab-pane {{ $loop->first? 'active' : ''}}" id="{{ \Str::slug($category) }}">
                            <table class="table color-table info-table table table-hover table-striped table-condensed settings-table">
                                <thead>
                                <tr>
                                    <th>@lang('Settings::labels.settings.label')</th>
                                    <th>@lang('Settings::labels.settings.value')</th>
                                    <th>@lang('Corals::attributes.updated_at')</th>
                                    <th>@lang('Corals::labels.action')</th>
                                </tr>
                                </thead>
                                <tbody class="settings-table-body">
                                @foreach(\Settings::getSortedSettings($settings) as $setting)
                                    <tr id="{{ $setting->hashed_id }}" data-hashed-id={{ $setting->hashed_id }}>
                                        <td>{{ $setting->present('label') }}</td>
                                        <td style="max-width: 500px;">{!! $setting->present('value')  !!}</td>
                                        <td>{{ $setting->present('updated_at') }}</td>
                                        <td>{!! $setting->present('action') !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            @endcomponent
        </div>
    </div>
@endsection


@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/TableDnD/1.0.5/jquery.tablednd.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $(".settings-table").tableDnD({
                onDrop: function () {
                    let orderedSettings = [];

                    let row = $('.settings-table tbody').find('tr');

                    row.each(function (index, item) {
                        orderedSettings.push({
                            display_order: index,
                            id: $(item).data('hashed-id')
                        })
                    });

                    $.post(`{{ url('settings/update-settings-order') }}`, {
                        'orderedSettings': orderedSettings
                    }).done(function (response) {
                        themeNotify(response);
                    }).fail(function (response, textStatus, jqXHR) {
                        handleAjaxSubmitError(response, textStatus, jqXHR, $(targetEl));
                    });
                },
            });
        });
    </script>
@endsection
