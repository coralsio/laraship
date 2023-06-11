@extends('layouts.master')

@section('title',$title)
@section('css')
    @php \Actions::do_action('dashboard_styles') @endphp
@endsection
@section('content_header')
    @component('components.content_header')

        @slot('page_title')
            {{ $title }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('dashboard') }}
        @endslot

    @endcomponent
@endsection

@section('content')
    @php \Actions::do_action('pre_display_dashboard') @endphp

    @component('components.box',['box_class'=>'no-block-ui'])
        {!! $dashboard_content !!}
    @endcomponent

    @php \Actions::do_action('post_display_dashboard') @endphp

@endsection

@section('js')
    @parent
    @php \Actions::do_action('dashboard_scripts') @endphp
@endsection