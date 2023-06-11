<!DOCTYPE html>
<html lang="en">
<head>
    {!! \SEO::generate() !!}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ \Settings::get('site_favicon') }}" type="image/png">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap -->
    {!! Theme::css('css/bootstrap.min.css') !!}
    {!! Theme::css('css/font-awesome.min.css') !!}
    {!! Theme::css('css/animate.min.css') !!}
    {!! Theme::css('css/prettyPhoto.css') !!}
    {!! Theme::css('plugins/toastr/toastr.min.css') !!}
    {!! Theme::css('plugins/Ladda/ladda-themeless.min.css') !!}
    {!! Theme::css('css/pricingTable.min.css') !!}
    {!! Theme::css('css/main.css') !!}
    {!! Theme::css('css/responsive.css') !!}
    {!! Theme::css('css/custom.css') !!}

    <!--[if lt IE 9]>
    {!! Theme::js('js/html5shiv.js') !!}
    {!! Theme::js('js/respond.min.js') !!}

    <![endif]-->
    <script type="text/javascript">
        window.base_url = '{!! url('/') !!}';
    </script>
    {!! \Html::script('assets/corals/js/corals_header.js') !!}
    {!! \Assets::css() !!}

    @if(\Settings::get('google_analytics_id'))
    <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async
                src="https://www.googletagmanager.com/gtag/js?id={{ \Settings::get('google_analytics_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());

            gtag('config', "{{ \Settings::get('google_analytics_id') }}");
        </script>
    @endif
    <style type="text/css">
        {!! \Settings::get('custom_css', '') !!}
    </style>
</head>
<body class="homepage">

@yield('css')
@stack('partial_css')

@include('partials.header')

@yield('before_content')

<div id="editable_content">
    @yield('editable_content')
</div>

@yield('after_content')

<div>@include('partials.footer')</div>
<!--/#footer-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
{!! Theme::js('js/jquery.js') !!}
{!! Theme::js('js/bootstrap.min.js') !!}
{!! Theme::js('js/jquery.prettyPhoto.js') !!}
{!! Theme::js('js/jquery.isotope.min.js') !!}
{!! Theme::js('js/wow.min.js') !!}
{!! Theme::js('plugins/toastr/toastr.min.js') !!}
{!! Theme::js('plugins/Ladda/spin.min.js') !!}
{!! Theme::js('plugins/Ladda/ladda.min.js') !!}
{!! Theme::js('js/functions.js') !!}
{!! Theme::js('js/main.js') !!}

{!! \Html::script('assets/corals/js/corals_functions.js') !!}

{!! \Html::script('assets/corals/js/corals_main.js') !!}

{!! Assets::js() !!}

@php  \Actions::do_action('footer_js') @endphp

@yield('js')

<script type="text/javascript">
    {!! \Settings::get('custom_js', '') !!}
</script>

<div id="global-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="global-modal_modalLabel"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header ">
                <h4 class="modal-title" id="global-modal_modalLabel"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" id="modal-body-global-modal">

            </div>
            <div class="modal-footer hidden">

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@include('Corals::corals_main')
</body>
</html>
