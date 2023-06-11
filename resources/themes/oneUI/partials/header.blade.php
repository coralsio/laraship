<!-- contains the header -->

<header id="page-header">
    <!-- Header Content -->
    <div class="content-header">
        <!-- Left Section -->
        <div class="d-flex align-items-center">
            <!-- Toggle Sidebar -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
            <button type="button" class="btn btn-sm btn-dual mr-2 d-lg-none" data-toggle="layout"
                    data-theme_action="sidebar_toggle">
                <i class="fa fa-fw fa-bars"></i>
            </button>
            <!-- END Toggle Sidebar -->

            <!-- Toggle Mini Sidebar -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
            <button type="button" class="btn btn-sm btn-dual mr-2 d-none d-lg-inline-block" data-toggle="layout"
                    data-theme_action="sidebar_mini_toggle">
                <i class="fa fa-fw fa-ellipsis-v"></i>
            </button>
            <!-- END Toggle Mini Sidebar -->

            <!-- Open Search Section (visible on smaller screens) -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <button type="button" class="btn btn-sm btn-dual d-md-none" data-toggle="layout"
                    data-theme_action="header_search_on">
                <i class="fa fa-fw fa-search"></i>
            </button>
            <!-- END Open Search Section -->

            <!-- Search Form (visible on larger screens) -->
            {{--            <form class="d-none d-md-inline-block" action="be_pages_generic_search.html" method="POST">--}}
            {{--                <div class="input-group input-group-sm">--}}
            {{--                    <input type="text" class="form-control form-control-alt" placeholder="Search.."--}}
            {{--                           id="page-header-search-input2" name="page-header-search-input2">--}}

            {{--                    <div class="input-group-append">--}}
            {{--                                    <span class="input-group-text bg-body border-0">--}}
            {{--                                        <i class="fa fa-fw fa-search"></i>--}}
            {{--                                    </span>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </form>--}}

{{--            <div class="d-none d-md-inline-block orders-search-bar" style="width: 320px">--}}
{{--                <select class="orders-search-bar-select2"></select>--}}
{{--            </div>--}}
            <!-- END Search Form -->
        </div>
        <!-- END Left Section -->

        <!-- Right Section -->
        <div class="d-flex align-items-center">
            <!-- User Dropdown -->
            <div class="dropdown d-inline-block ml-2">
                <button type="button" class="btn btn-sm btn-dual d-flex align-items-center"
                        id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                    <img class="rounded-circle" src="{{user()->picture}}" alt=""
                         style="width: 21px;">
                    <span class="d-none d-sm-inline-block ml-2">{{user()->full_name}}</span>
                    <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block ml-1 mt-1"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right p-0 border-0"
                     aria-labelledby="page-header-user-dropdown">
                    <div class="p-3 text-center bg-primary-dark rounded-top">
                        <img class="img-avatar img-avatar48 img-avatar-thumb"
                             src="{{user()->picture}}" alt="">
                        <p class="mt-2 mb-0 text-white font-w500">{{user()->full_name}}</p>
                        <p class="mb-0 text-white-50 font-size-sm">{{user()->job_title}}</p>
                    </div>
                    <div class="p-2">

                        <a class="dropdown-item d-flex align-items-center justify-content-between"
                           href="{{url('profile')}}">
                            <span class="font-size-sm font-w500">Profile</span>
                        </a>
                        {{--                        <a class="dropdown-item d-flex align-items-center justify-content-between"--}}
                        {{--                           href="javascript:void(0)">--}}
                        {{--                            <span class="font-size-sm font-w500">Settings</span>--}}
                        {{--                        </a>--}}
                        <div role="separator" class="dropdown-divider"></div>
{{--                        <a class="dropdown-item d-flex align-items-center justify-content-between"--}}
{{--                           href="{{url('timed-out')}}">--}}
{{--                            <span class="font-size-sm font-w500">Lock Account</span>--}}
{{--                        </a>--}}
                        <a class="dropdown-item d-flex align-items-center justify-content-between"
                           href="{{ route('logout') }}" data-action="logout">
                            <span class="font-size-sm font-w500">Log Out</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- END User Dropdown -->

            <!-- Notifications Dropdown -->
            @if (schemaHasTable('notifications'))
                <div class="dropdown d-inline-block ml-2">
                    <a href="{{url('notifications')}}"
                       class="btn btn-sm btn-dual" id="page-header-notifications-dropdown"
                       data-_toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-fw fa-bell"></i>
                        @if(user()->unreadNotifications()->count())
                            <span class="text-primary">•</span>
                        @endif
                    </a>
                </div>
        @endif
        <!-- END Notifications Dropdown -->

            <!-- Toggle Side Overlay -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->

            <!-- END Toggle Side Overlay -->
        </div>
        <!-- END Right Section -->
    </div>
    <!-- END Header Content -->

    <!-- Header Search -->
    <div id="page-header-search" class="overlay-header bg-white">
        <div class="content-header">
            <div class="input-group">
                <div class="input-group-prepend">
                    {{--                        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->--}}
                    <button type="button" class="btn btn-alt-danger" data-toggle="layout"
                            data-theme_action="header_search_off">
                        <i class="fa fa-fw fa-times-circle"></i>
                    </button>
                </div>


                <div class="orders-search-bar">
                    <select class="orders-search-bar-select2"></select>
                </div>

            </div>

        </div>
    </div>
    <!-- END Header Search -->

    <!-- Header Loader -->
    <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
    <div id="page-header-loader" class="overlay-header bg-white">
        <div class="content-header">
            <div class="w-100 text-center">
                <i class="fa fa-fw fa-circle-notch fa-spin"></i>
            </div>
        </div>
    </div>
    <!-- END Header Loader -->
</header>