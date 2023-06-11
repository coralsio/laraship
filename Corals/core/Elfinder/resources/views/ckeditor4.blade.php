@extends('layouts.blank')

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
        // Helper function to get parameters from the query string.
        function getUrlParam(paramName) {
            var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
            var match = window.location.search.match(reParam) ;
            return (match && match.length > 1) ? match[1] : '' ;
        }
        $().ready(function() {
            var funcNum = getUrlParam('CKEditorFuncNum');
            var elf = $('#elfinder').elfinder({
                // set your elFinder options here
                <?php if($locale){ ?>
                lang: '<?= $locale ?>', // locale
                <?php } ?>
                customData: {
                    _token: '<?= csrf_token() ?>'
                },
                url: '{{ route("file-manager.connector") }}',  // connector URL
                soundPath: '<?= asset($dir.'/sounds') ?>',
                getFileCallback : function(file) {
                    window.opener.CKEDITOR.tools.callFunction(funcNum, file.url);
                    window.close();
                }
            }).elfinder('instance');
        });
    </script>
@endsection
