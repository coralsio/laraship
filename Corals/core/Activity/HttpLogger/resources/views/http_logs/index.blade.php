@extends('layouts.crud.index')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('http_log') }}
        @endslot
    @endcomponent
@endsection

@section('actions')
    @parent
    {!! CoralsForm::link(url($resource_url.'/purge'), "Purge History Data", ['class'=>'btn btn-warning btn-sm', 'data-action'=>'post']) !!}
@endsection
