<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ user()->picture_thumb }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><a href="{{ url('profile') }}" title="Profile">{{ user()->name }}</a></p>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="{{ \Request::is('dashboard')?'active':'' }}">
                <a href="{{ url('dashboard') }}">
                    @lang('corals-admin::labels.partial.dashboard')
                </a>
            </li>
            @include('partials.menu.menu_item', ['menus'=>Menus::getMenu('sidebar','active') ])
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
