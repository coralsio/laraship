@extends('layouts.master')

@section('title',$title)

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('cache') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @component('components.box')
        <div class="row">
            <div class="col-md-7">
                <div class="table-responsive">
                    <table class="table table-stripe">
                        <thead>
                        <tr>
                            <th>@lang('Settings::labels.cash_management.description')</th>
                            <th width="200">@lang('Settings::labels.cash_management.command')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(config('settings.supported_commands', []) as $command => $details)
                            <tr>
                                <td>{{ $details['text'] }}</td>
                                <td>
                                    {!! CoralsForm::link(url('cache-management/'.$command), "<b>$command</b>", [
                                    'class'=>'laddaBtn btn-block btn-sm btn '.$details['class'],
                                    'data'=>['action'=>"post",'page_action'=>'site_reload']]) !!}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endcomponent
@endsection