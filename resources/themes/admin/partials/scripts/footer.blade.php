<!-- jQuery 3 -->
{!! Theme::js('plugins/jquery/dist/jquery.min.js') !!}
<!-- Bootstrap 3.3.7 -->
{!! Theme::js('plugins/bootstrap/dist/js/bootstrap.min.js') !!}
{!! Theme::js('assets/corals/plugins/lodash/lodash.js') !!}

{!! Assets::js() !!}

<!-- iCheck -->
{!! Theme::js('plugins/iCheck/icheck.min.js') !!}
<!-- Pace -->
{{--{!! Theme::js('plugins/pace/pace.min.js') !!}--}}

<!-- Jquery BlockUI -->
{!! Theme::js('plugins/jquery-block-ui/jquery.blockUI.min.js') !!}

<!-- Ladda -->
{!! Theme::js('plugins/Ladda/spin.min.js') !!}
{!! Theme::js('plugins/Ladda/ladda.min.js') !!}

<!-- toastr -->
{!! Theme::js('plugins/toastr/toastr.min.js') !!}
<!-- SlimScroll -->
{!! Theme::js('plugins/jquery-slimscroll/jquery.slimscroll.min.js') !!}
<!-- FastClick -->
{!! Theme::js('plugins/fastclick/lib/fastclick.js') !!}

{!! Theme::js('plugins/sweetalert2/dist/sweetalert2.all.min.js') !!}
{!! Theme::js('plugins/select2/dist/js/select2.full.min.js') !!}
<!-- AdminLTE App -->
{!! Theme::js('js/adminlte.min.js') !!}
{!! \Html::script('assets/corals/plugins/moment/moment.min.js') !!}

{!! Theme::js('js/functions.js') !!}
{!! Theme::js('js/main.js?v=v1') !!}
<!-- corals js -->
{!! Theme::js('assets/corals/plugins/lodash/lodash.js') !!}
{!! \Html::script('assets/corals/plugins/lightbox2/js/lightbox.min.js') !!}
{!! \Html::script('assets/corals/plugins/clipboard/clipboard.min.js') !!}
@if(config('corals.query_builder_enabled'))
    {!! Html::script('assets/corals/plugins/queryBuilder/js/query-builder.min.js') !!}
@endif
{!! \Html::script('assets/corals/js/corals_functions.js') !!}
{!! \Html::script('assets/corals/js/corals_main.js') !!}

@include('Corals::corals_main')

@yield('js')

@php  \Actions::do_action('admin_footer_js') @endphp

@include('partials.notifications')
