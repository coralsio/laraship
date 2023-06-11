<!-- jQuery 3 -->
{!! Theme::js('plugins/jquery/dist/jquery.min.js') !!}

{!! Theme::js('plugins/lodash/lodash.js') !!}


<!-- iCheck -->
{!! Theme::js('plugins/iCheck/icheck.min.js') !!}
<!-- Pace -->
{!! Theme::js('plugins/pace/pace.min.js') !!}

{!! Theme::js('js/oneui.app.min.js') !!}
{!! Theme::js('js/oneui.core.min.js') !!}

{!! Assets::js() !!}

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

{!! \Html::script('assets/corals/plugins/moment/moment.min.js') !!}

{!! Theme::js('js/functions.js') !!}
{!! Theme::js('js/main.js') !!}
<!-- corals js -->
{!! Theme::js('plugins/lodash/lodash.js') !!}
{!! \Html::script('assets/corals/plugins/lightbox2/js/lightbox.min.js') !!}
{!! \Html::script('assets/corals/plugins/clipboard/clipboard.min.js') !!}
{!! Html::script('assets/corals/plugins/queryBuilder/js/query-builder.standalone.js') !!}
{!! \Html::script('assets/corals/js/corals_functions.js') !!}
{!! \Html::script('assets/corals/js/corals_main.js') !!}
{{--{!! Html::script('assets/corals/plugins/lockScreen/js/lockScreen.js') !!}--}}


@include('Corals::corals_main')

@yield('js')

@php  \Actions::do_action('admin_footer_js') @endphp

@include('partials.notifications')
