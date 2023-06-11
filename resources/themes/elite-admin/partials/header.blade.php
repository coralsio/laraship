<!-- ============================================================== -->
<!-- Topbar header - style you can find in pages.scss -->
<!-- ============================================================== -->
<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <!-- ============================================================== -->
        <!-- Logo -->
        <!-- ============================================================== -->
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ url('/') }}">
                <!-- Logo icon --><b>
                    <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                    <!-- Dark Logo icon -->
                    <img src="{{ \Settings::get('site_logo') }}" alt="{{ \Settings::get('site_name') }}"
                         class="dark-logo"/>
                    <!-- Light Logo icon -->
                    <img src="{{ \Settings::get('site_logo_white') }}" alt="{{ \Settings::get('site_name') }}"
                         class="light-logo"/>
                </b>
                <!--End Logo icon -->
            </a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav mr-auto">
                <!-- This is  -->
                <li class="nav-item"><a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark"
                                        href="javascript:void(0)"><i class="fa fa-bars"></i></a></li>
                <li class="nav-item"><a class="nav-link sidebartoggler d-none waves-effect waves-dark"
                                        href="javascript:void(0)"><i class="fa fa-bars"></i></a></li>
            </ul>
            <div class="navbar-nav modules-custom-nav">
                @php Actions::do_action('show_navbar') @endphp
            </div>
            <ul class="navbar-nav my-lg-0 mr-2">
                @if(count(\Settings::get('supported_languages', [])) > 1)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-dark" href="#" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            <small>{!! \Language::flag() !!}</small>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right animated bounceInDown p-3">
                            {!! \Language::flags('list-unstyled','mb-1') !!}
                        </div>
                    </li>
                @endif
                @if (schemaHasTable('announcements'))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-dark" href="#"
                           id="announcements_header_dropdown"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-fw fa-bullhorn"></i>
                            @if($unreadAnnouncements = \Announcement::unreadAnnouncements())
                                {{ $unreadAnnouncements }}
                                <div class="notify"><span class="heartbit"></span>
                                    <span class="point"></span></div>
                            @endif
                        </a>
                        <div class="dropdown-menu mailbox dropdown-menu-right animated bounceInDown"
                             aria-labelledby="announcements_header_dropdown">
                            <ul>
                                @if($unreadAnnouncements)
                                    <li>
                                        <div class="drop-title">@lang('Announcement::labels.unread_count_message',['count'=>$unreadAnnouncements])</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            @foreach(\Announcement::unreadAnnouncements(user(), false, 5) as $announcement)
                                                <a href="{{ $announcement->getShowURL() }}"
                                                   class="show_announcement"
                                                   data-ann_hashed_id="{{ $announcement->hashed_id }}"
                                                   data-title="{{ $announcement->title }}">
                                                    @if($announcement->image)
                                                        <div class="user-img">
                                                            <img src="{{ $announcement->image }}" alt="ann"
                                                                 class="img-fluid">
                                                        </div>
                                                    @else
                                                        <div class="btn btn-info btn-circle">
                                                            <i class="fa fa-bullhorn"></i>
                                                        </div>
                                                    @endif
                                                    <div class="mail-contnet">
                                                        <span class="mail-desc">{{ $announcement->title }}</span>
                                                        <span class="time">{{ $announcement->starts_at->diffForHumans() }}</span>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </li>
                                @endif
                                <li>
                                    <a class="nav-link text-center link" href="{{ url('announcements') }}">
                                        <strong>@lang('Announcement::labels.see_all')</strong>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if (schemaHasTable('notifications'))
                    <li class="nav-item">
                        <a class="nav-link waves-effect waves-dark"
                           href="{{ url('notifications') }}"
                           aria-expanded="false">
                            <i class="fa fa-fw fa-bell"></i>
                            @if($unreadNotifications = user()->unreadNotifications()->count())
                                {{ $unreadNotifications }}
                                <div class="notify"><span class="heartbit"></span>
                                    <span class="point"></span></div>
                            @endif
                        </a>
                    </li>
                @endif
                @if (user()->can('Settings::module.manage') && !config('settings.models.module.disable_update'))
                    <li class="nav-item">
                        <a href="{{ url('modules') }}" class="nav-link waves-effect waves-dark"
                           aria-expanded="false">
                            <i class="fa fa-fw fa-refresh"></i>
                            @if($updatesAvailable = \Modules::hasUpdates())
                                {{ $updatesAvailable  }}
                                <div class="notify"><span class="heartbit"></span> <span
                                            class="point"></span></div>
                            @endif
                        </a>
                    </li>
                @endif

                <li class="nav-item dropdown u-pro">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="#"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                                src="{{ user()->picture_thumb }}" alt="{{ user()->name }}" class="">
                        <span class="hidden-md-down">{{ user()->name }} <i class="fa fa-angle-down"></i></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animated flipInY">
                        <a href="{{ url('profile') }}" class="dropdown-item">
                            <i class="fa fa-user-o"></i>
                            @lang('corals-elite-admin::labels.partial.profile')
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" data-action="logout"
                           class="dropdown-item">
                            <i class="fa fa-power-off"></i>
                            @lang('corals-elite-admin::labels.partial.logout')
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!-- ============================================================== -->
<!-- End Topbar header -->
<!-- ============================================================== -->
