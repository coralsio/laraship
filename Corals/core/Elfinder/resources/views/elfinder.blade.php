@extends('layouts.master')

@section('title',$title)
@section('css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"/>
    <!-- elFinder CSS (REQUIRED) -->
    <link rel="stylesheet" type="text/css" href="{{ asset($dir.'/css/elfinder.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset($dir.'/css/theme.css') }}">
@endsection
@section('content_header')
    @component('components.content_header')

        @slot('page_title')
            {{ $title }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('file-manager') }}
        @endslot

    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Element where elFinder will be created (REQUIRED) -->
            <div id="elfinder"></div>
        </div>
    </div>
@endsection

@section('js')
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

    <!-- elFinder JS (REQUIRED) -->
    <script src="{{ asset($dir.'/js/elfinder.min.js') }}"></script>

    @if($locale)
        <!-- elFinder translation (OPTIONAL) -->
        <script src="{{ asset($dir."/js/i18n/elfinder.$locale.js") }}"></script>
    @endif

    <!-- elFinder initialization (REQUIRED) -->
    <script type="text/javascript" charset="utf-8">
        // Documentation for client options:
        // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
        $().ready(function () {
            $('#elfinder').elfinder({
                // set your elFinder options here
                @if($locale)
                lang: '{{ $locale }}', // locale
                @endif
                customData: {
                    _token: '{{ csrf_token() }}'
                },
                url: '{{ route("file-manager.connector") }}',  // connector URL
                soundPath: '{{ asset($dir.'/sounds') }}'
            });
        });
    </script>
@endsection
