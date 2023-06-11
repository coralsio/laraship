<nav id="sidebar" aria-label="Main Navigation">
    <!-- Side Header -->
    <div class="content-header bg-white-5">
        <!-- Logo -->
        <a class="font-w600 text-dual" href="{{url('/')}}">
                        <span class="smini-visible">
                            <i class="fa fa-circle-notch text-primary"></i>
                        </span>
            <span class="smini-hide font-size-h5 tracking-wider">

                        {{ \Settings::get('site_name', 'Corals') }}
                        </span>
        </a>
        <!-- END Logo -->

        <!-- Extra -->
        <div>
            <!-- Options -->
            <div class="dropdown d-inline-block ml-2">

                <div class="dropdown-menu dropdown-menu-right font-size-sm smini-hide border-0"
                     aria-labelledby="sidebar-themes-dropdown">
                    <!-- Color Themes -->
                    <!-- Layout API, functionality initialized in Template._uiHandleTheme() -->
                    <a class="dropdown-item d-flex align-items-center justify-content-between font-w500"
                       data-toggle="theme" data-theme="default" href="#">
                        <span>Default</span>
                        <i class="fa fa-circle text-default"></i>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between font-w500"
                       data-toggle="theme" data-theme="assets/css/themes/amethyst.min.css" href="#">
                        <span>Amethyst</span>
                        <i class="fa fa-circle text-amethyst"></i>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between font-w500"
                       data-toggle="theme" data-theme="assets/css/themes/city.min.css" href="#">
                        <span>City</span>
                        <i class="fa fa-circle text-city"></i>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between font-w500"
                       data-toggle="theme" data-theme="assets/css/themes/flat.min.css" href="#">
                        <span>Flat</span>
                        <i class="fa fa-circle text-flat"></i>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between font-w500"
                       data-toggle="theme" data-theme="assets/css/themes/modern.min.css" href="#">
                        <span>Modern</span>
                        <i class="fa fa-circle text-modern"></i>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between font-w500"
                       data-toggle="theme" data-theme="assets/css/themes/smooth.min.css" href="#">
                        <span>Smooth</span>
                        <i class="fa fa-circle text-smooth"></i>
                    </a>
                    <!-- END Color Themes -->

                    <div class="dropdown-divider"></div>

                    <!-- Sidebar Styles -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <a class="dropdown-item font-w500" data-toggle="layout" data-theme_action="sidebar_style_light"
                       href="#">
                        <span>Sidebar Light</span>
                    </a>
                    <a class="dropdown-item font-w500" data-toggle="layout" data-theme_action="sidebar_style_dark"
                       href="#">
                        <span>Sidebar Dark</span>
                    </a>
                    <!-- Sidebar Styles -->

                    <div class="dropdown-divider"></div>

                    <!-- Header Styles -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <a class="dropdown-item font-w500" data-toggle="layout" data-theme_action="header_style_light"
                       href="#">
                        <span>Header Light</span>
                    </a>
                    <a class="dropdown-item font-w500" data-toggle="layout" data-theme_action="header_style_dark"
                       href="#">
                        <span>Header Dark</span>
                    </a>
                    <!-- Header Styles -->
                </div>
            </div>
            <!-- END Options -->

            <!-- Close Sidebar, Visible only on mobile screens -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <a class="d-lg-none btn btn-sm btn-dual ml-1" data-toggle="layout" data-theme_action="sidebar_close"
               href="javascript:void(0)">
                <i class="fa fa-fw fa-times"></i>
            </a>
            <!-- END Close Sidebar -->
        </div>
        <!-- END Extra -->
    </div>
    <!-- END Side Header -->

    <!-- Sidebar Scrolling -->
    <div class="js-sidebar-scroll">
        <!-- Side Navigation -->
        <div class="content-side">
            <ul class="nav-main">
                <li class="nav-main-item {{ \Request::is('dashboard')?'active':'' }}">
                    <a href="{{ url('dashboard') }}" class="nav-main-link active">
                        <i class="nav-main-link-icon si si-speedometer"></i>
                        <span class="nav-main-link-name">
                            @lang('corals-one-ui::labels.partial.dashboard')
                        </span>
                    </a>
                </li>
                @include('partials.menu.menu_item', ['menus'=>Menus::getMenu('sidebar','active') ])
            </ul>
        </div>
        <!-- END Side Navigation -->
    </div>
    <!-- END Sidebar Scrolling -->
</nav>
{{--<aside class="main-sidebar">--}}
{{--    <!-- sidebar: style can be found in sidebar.less -->--}}
{{--    <section class="sidebar">--}}
{{--        <!-- Sidebar user panel -->--}}
{{--        <div class="user-panel">--}}
{{--            <div class="pull-left image">--}}
{{--                <img src="{{ user()->picture_thumb }}" class="img-circle" alt="User Image">--}}
{{--            </div>--}}
{{--            <div class="pull-left info">--}}
{{--                <p><a href="{{ url('profile') }}" title="Profile">{{ user()->name }}</a></p>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <!-- sidebar menu: : style can be found in sidebar.less -->--}}
{{--        <ul class="sidebar-menu" data-widget="tree">--}}
{{--            <li class="{{ \Request::is('dashboard')?'active':'' }}">--}}
{{--                <a href="{{ url('dashboard') }}">--}}
{{--                    @lang('corals-admin::labels.partial.dashboard')--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            @include('partials.menu.menu_item', ['menus'=>Menus::getMenu('sidebar','active') ])--}}
{{--        </ul>--}}
{{--    </section>--}}
{{--    <!-- /.sidebar -->--}}
{{--</aside>--}}
