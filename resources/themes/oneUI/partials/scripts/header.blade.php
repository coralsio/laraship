<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">

{!! Theme::css('plugins/select2/dist/css/select2.min.css') !!}


{!! Theme::css('css/oneui.css') !!}

{!! Theme::css('plugins/font-awesome/css/font-awesome.min.css') !!}

{{--iCheck--}}
{!! Theme::css('plugins/iCheck/all.css') !!}

<!-- Ladda  -->
{!! Theme::css('plugins/Ladda/ladda-themeless.min.css') !!}

<!-- toastr -->
{!! Theme::css('plugins/toastr/toastr.min.css') !!}
<!-- sweetalert2 -->
{!! Theme::css('plugins/sweetalert2/dist/sweetalert2.css') !!}
{!! \Html::style('assets/corals/plugins/lightbox2/css/lightbox.min.css') !!}
{!! \Html::style('assets/corals/plugins/queryBuilder/css/query-builder.default.css') !!}


{!! \Assets::css() !!}


{!! Theme::css('css/custom.css') !!}


@yield('css')

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
