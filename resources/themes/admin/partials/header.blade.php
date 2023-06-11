<!-- contains the header -->
<header class="main-header">
    <!-- Logo -->
    <a href="{{ url('/') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>C</b>o</span>
        <!-- logo for regular state and mobile devices -->
        <img src="{{ \Settings::get('site_logo') }}" class="" style="max-height: 30px;"/>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">@lang('corals-admin::labels.partial.toggle_navigation')</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                @php Actions::do_action('show_navbar') @endphp
                @if(count(\Settings::get('supported_languages', [])) > 1)
                    <li class="dropdown locale">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            {!! \Language::flag() !!} <span id="language_name"> {!! \Language::getName() !!}</span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        {!! \Language::flags('dropdown-menu') !!}
                    </li>
                @endif
                @if (schemaHasTable('announcements'))
                    <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bullhorn"></i>
                            @if($unreadAnnouncements = \Announcement::unreadAnnouncements())
                                <span class="label label-success">{{ $unreadAnnouncements }}</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu">
                            @if($unreadAnnouncements)
                                <li class="header text-center">
                                    <small>@lang('Announcement::labels.unread_count_message',['count'=>$unreadAnnouncements])</small>
                                </li>
                                @foreach(\Announcement::unreadAnnouncements(user(), false, 5) as $announcement)
                                    <li>
                                        <!-- inner menu: contains the actual data -->
                                        <ul class="menu">
                                            <li><!-- start message -->
                                                <a href="{{ $announcement->getShowURL() }}"
                                                   class="show_announcement"
                                                   data-ann_hashed_id="{{ $announcement->hashed_id }}"
                                                   data-title="{{ $announcement->title }}">
                                                    @if($announcement->image)
                                                        <div class="pull-left">
                                                            <img src="{{ $announcement->image }}" class="img-responsive"
                                                                 alt="ann-img">
                                                        </div>
                                                    @else
                                                        <div class="pull-left btn btn-info btn-circle m-r-10">
                                                            <i class="fa fa-bullhorn"></i>
                                                        </div>
                                                    @endif
                                                    <h4 style="text-overflow: ellipsis;overflow: hidden;">
                                                        {{ $announcement->title }}
                                                    </h4>
                                                    <p>
                                                        <small>
                                                            <i class="fa fa-clock-o"></i> {{ $announcement->starts_at->diffForHumans() }}
                                                        </small>
                                                    </p>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                @endforeach
                            @endif
                            <li class="footer">
                                <a href="{{ url('announcements') }}">@lang('Announcement::labels.see_all')</a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (schemaHasTable('notifications'))

                    <li class="dropdown notifications-menu">
                        <a href="{{ url('notifications') }}" class="_dropdown-toggle" data-_toggle="dropdown">
                            <i class="fa fa-bell"></i>
                            @if($unreadNotifications = user()->unreadNotifications()->count())
                                <span class="label label-warning badge badge-warning">{{ $unreadNotifications }}</span>
                            @endif
                        </a>
                    </li>
                @endif
                @if (user()->can('Settings::module.manage') && !config('settings.models.module.disable_update'))
                    <li class="dropdown">
                        <a href="{{ url('modules') }}" class="_dropdown-toggle" data-_toggle="dropdown">
                            <i class="fa fa-refresh"></i>
                            @if($updatesAvailable = \Modules::hasUpdates())
                                <span class="label label-info">{{ $updatesAvailable }}</span>
                            @endif
                        </a>
                    </li>
                @endif
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ user()->picture_thumb }}" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{ user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{ user()->picture_thumb }}" class="img-circle"
                                 alt="User Image">

                            <p>
                                {{ user()->name }}
                            </p>
                            <p>
                                {{ user()->email }}
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ url('profile') }}"
                                   class="btn btn-default btn-flat">@lang('corals-admin::labels.partial.profile')</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('logout') }}" data-action="logout"
                                   class="btn btn-default btn-flat">
                                    @lang('corals-admin::labels.partial.logout')
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>