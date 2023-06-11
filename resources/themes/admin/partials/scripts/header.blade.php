<!-- Bootstrap 3.3.7 -->
{!! Theme::css('plugins/bootstrap/dist/css/bootstrap.min.css') !!}
<!-- animate.css -->
{!! Theme::css('plugins/animate.css/animate.min.css') !!}
<!-- Font Awesome -->
{!! Theme::css('plugins/font-awesome/css/font-awesome.min.css') !!}

{!! Theme::css('plugins/select2/dist/css/select2.min.css') !!}
<!-- Theme style -->
{!! Theme::css('css/AdminLTE.css') !!}
<!-- iCheck -->
{!! Theme::css('plugins/iCheck/all.css') !!}
<!-- AdminLTE Skins. Choose a skin from the css/skins -->

<!-- Pace style -->
{!! Theme::css('plugins/pace/pace.min.css') !!}

<!-- Ladda  -->
{!! Theme::css('plugins/Ladda/ladda-themeless.min.css') !!}


<!-- toastr -->
{!! Theme::css('plugins/toastr/toastr.min.css') !!}
<!-- sweetalert2 -->
{!! Theme::css('plugins/sweetalert2/dist/sweetalert2.css') !!}
{!! \Html::style('assets/corals/plugins/lightbox2/css/lightbox.min.css') !!}
{!! \Html::style('assets/corals/plugins/queryBuilder/css/query-builder.default.css') !!}

{!! Theme::css('css/core.css') !!}
{!! Theme::css('css/custom.css') !!}



{!! \Assets::css() !!}

@if(\Language::isRTL())
    {!! Theme::css('css/style-rtl.css') !!}
    {!! Theme::css('plugins/bootstrap/dist/css/bootstrap-rtl.css') !!}

@endif


@yield('css')
@stack('partial_css')

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></link>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Google Font -->
<link rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

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
