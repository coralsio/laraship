<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="{{ \Request::is('dashboard')?'active':'' }}">
                    <a class="waves-effect waves-dark" href="{{ url('dashboard') }}">
                        @lang('corals-elite-admin::labels.partial.dashboard')
                    </a>
                </li>
                @include('partials.menu.menu_item', ['menus'=>Menus::getMenu('sidebar','active') ])
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->