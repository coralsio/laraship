<!-- ============================================================== -->
{!! Theme::js('plugins/jquery/jquery-3.2.1.min.js') !!}
<!-- Bootstrap tether Core JavaScript -->
{!! Theme::js('plugins/popper/popper.min.js') !!}
{!! Theme::js('plugins/bootstrap/dist/js/bootstrap.min.js') !!}
<!-- slimscrollbar scrollbar JavaScript -->
{!! Theme::js('js/perfect-scrollbar.jquery.min.js') !!}
<!--Wave Effects -->
{!! Theme::js('js/waves.js') !!}
<!--Menu sidebar -->
{!! Theme::js('js/sidebarmenu.js') !!}
<!--stickey kit -->
{!! Theme::js('plugins/sticky-kit-master/dist/sticky-kit.min.js') !!}
<!--Custom JavaScript -->
{!! Theme::js('js/custom.min.js') !!}

{!! Theme::js('plugins/toast-master/js/jquery.toast.js') !!}
{!! Theme::js('plugins/select2/dist/js/select2.full.min.js') !!}
{!! Theme::js('plugins/sweetalert2/dist/sweetalert2.min.js') !!}
{!! Theme::js('assets/corals/plugins/lodash/lodash.js') !!}
<!-- Ladda -->
{!! Theme::js('plugins/Ladda/spin.min.js') !!}
{!! Theme::js('plugins/Ladda/ladda.min.js') !!}

{!! Assets::js() !!}

{!! Theme::js('js/functions.js') !!}
{!! Theme::js('js/main.js') !!}

<!-- corals js -->
{!! \Html::script('assets/corals/plugins/lightbox2/js/lightbox.min.js') !!}
@if(config('corals.query_builder_enabled'))
    {!! Html::script('assets/corals/plugins/queryBuilder/js/query-builder.min.js') !!}
@endif

{!! \Html::script('assets/corals/plugins/clipboard/clipboard.min.js') !!}
{!! \Html::script('assets/corals/js/corals_functions.js') !!}
{!! \Html::script('assets/corals/js/corals_main.js') !!}

@include('Corals::corals_main')

@yield('js')

@php  \Actions::do_action('admin_footer_js') @endphp

@include('partials.notifications')
