{!! Theme::css('plugins/toast-master/css/jquery.toast.css') !!}
{!! Theme::css('plugins/select2/dist/css/select2.min.css') !!}
{!! Theme::css('plugins/sweetalert2/dist/sweetalert2.css') !!}

@if(\Language::getDirection() == 'rtl')
    {!! Theme::css('css/style_rtl.min.css') !!}
@else
    {!! Theme::css('css/style.min.css') !!}
@endif

{!! Theme::css('css/floating-label.css') !!}
<!-- Ladda  -->
{!! Theme::css('plugins/Ladda/ladda-themeless.min.css') !!}
{!! \Html::style('assets/corals/plugins/lightbox2/css/lightbox.min.css') !!}
{!! \Html::style('assets/corals/plugins/queryBuilder/css/query-builder.default.css') !!}


{!! \Assets::css() !!}

{!! Theme::css('css/custom.css') !!}

@if(\Language::getDirection() == 'rtl')
    {!! Theme::css('css/custom_rtl.css') !!}
@endif

@yield('css')
@stack('partial_css')


<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<script type="text/javascript">
    window.base_url = '{!! url('/') !!}';
</script>

{!! \Html::script('assets/corals/js/corals_header.js') !!}

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
