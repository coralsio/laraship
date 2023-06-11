@extends('layouts.master')

@section('title',$title)

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('themes') }}
        @endslot
    @endcomponent
@endsection

@section('actions')
    {!! CoralsForm::link(url('http://laraship.com/contact/'),'Theme::labels.theme.theme_contact',['class'=>'btn btn-success','target'=>'_blank']) !!}
    {!! CoralsForm::link(url($resource_url.'/add'),'Theme::labels.theme.theme_add_new',['class'=>'btn btn-primary modal-load','data-title'=>trans('Theme::labels.theme.add_new_theme')]) !!}
    {!! CoralsForm::link(url($resource_url.'?check-for-updates=true'),'Theme::labels.theme.theme_check_update',['class'=>'btn btn-success']) !!}
@endsection

@section('content')
    @if($remote_updates)
        <div id="update_notification" class="alert alert-info">
            <button type="button"  class="close m-t-20" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            @lang('Theme::labels.theme.theme_update_notification')
        </div>
    @endif
    @component('components.box')
        @slot('box_title')
            @lang('Theme::labels.theme.box_title')
        @endslot
        <style>
            .theme-thumb{
                width:200px;
                height: 112px;
            }

        </style>
        <div class="row">
            <div class="col-md-12">
                <ul class="list-inline">
                    @foreach($admin_themes as $theme)
                        <li class="p-10 p-3">
                            <h4>{{ $theme->caption }}<br>
                                <small class="text-primary text-md ">{{$theme->name}}
                                    (v{{$theme->version}})
                                </small>
                            </h4>
                            <img src="{{ asset($theme->assetPath.'/thumb.png') }}"

                                 class="img-responsive img-fluid theme-thumb"
                                 alt="{{ $theme->caption }}"/>
                            <div class="m-t-10 m-b-10">
                                {!! \Theme::formatThemeActions($theme, 'admin', $remote_updates) !!}
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endcomponent

    @component('components.box')
        @slot('box_title')
            @lang('Theme::labels.theme.box_cms_title')
        @endslot
        <div class="row">
            <div class="col-md-12">
                <ul class="list-inline">
                    @foreach($frontend_themes as $theme)
                        <li class="p-10 p-1">
                            <h4>{{ $theme->caption }}
                                <br>
                                <small class="text-primary" style="font-size: 60%;">{{$theme->name}}
                                    (v{{$theme->version}})
                                </small>
                            </h4>

                            <img src="{{ asset($theme->assetPath.'/thumb.png') }}"
                                 style="width:200px; height: 112px;"
                                 class="img-responsive img-fluid"
                                 alt="{{ $theme->caption }}"/>
                            <div class="m-t-10 m-b-10">
                                {!! \Theme::formatThemeActions($theme, 'frontend', $remote_updates) !!}
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endcomponent
@endsection
