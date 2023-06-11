<header id="header">
    <nav class="navbar navbar-fixed-top" role="banner">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">@lang('corals-basic::labels.partial.toggle_navigation')</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand logo" href="{{ url('/') }}"><img class="site_logo"
                                                                        src="{{ \Settings::get('site_logo') }}"></a>
            </div>

            <div class="collapse navbar-collapse navbar-right">
                <ul class="nav navbar-nav">
                    @include('partials.menu.menu_item', ['menus' => Menus::getMenu('frontend_top','active')])
                    @auth
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                               role="button" aria-haspopup="true" aria-expanded="false">
                                {{ user()->name }}
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li class="">
                                    <a href="{{ url('dashboard') }}">
                                        <i class="fa fa-dashboard fa-fw"></i>
                                        @lang('corals-basic::labels.partial.dashboard')
                                    </a>
                                </li>
                                <li class="">
                                    <a href="{{ url('profile') }}"><i class="fa fa-user fa-fw"></i> @lang('corals-basic::labels.partial.profile')</a>
                                </li>
                                <li>
                                    <a href="{{ route('logout') }}" data-action="logout">
                                        <i class="fa fa-sign-out fa-fw"></i> @lang('corals-basic::labels.partial.logout')
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                               role="button" aria-haspopup="true" aria-expanded="false">
                                @lang('corals-basic::labels.partial.account')
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li class="">
                                    <a href="{{ route('login') }}">
                                        <i class="fa fa-sign-in fa-fw"></i>
                                        @lang('corals-basic::labels.partial.login')
                                    </a>
                                </li>
                                <li class="">
                                    <a href="{{ route('register') }}">
                                        <i class="fa fa-user fa-fw"></i>
                                        @lang('corals-basic::labels.partial.register')
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endauth
                    @php \Actions::do_action('post_display_frontend_menu') @endphp

                </ul>
            </div>
        </div><!--/.container-->
    </nav><!--/nav-->

</header><!--/header-->