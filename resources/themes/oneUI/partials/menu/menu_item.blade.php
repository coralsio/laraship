@foreach($menus as $menu)
    @if($menu->hasChildren('active') && $menu->user_can_access)
        <li class="nav-main-item {{ \Request::is(explode(',',$menu->active_menu_url))|| $menu->getProperty('always_active',false,'boolean') ?'active open':'' }}">
            <a href="#" class="nav-main-link nav-main-link-submenu"
               data-toggle="submenu">
                @if($menu->icon)<i class="{{ $menu->icon }} fa-fw nav-main-link-icon "></i>@endif
                <span class="nav-main-link-name">{{ $menu->name }}</span>
            </a>
            <ul class="nav-main-submenu">
                @include('partials.menu.menu_item', ['menus'=>$menu->getChildren('active')])
            </ul>
        </li>
    @elseif($menu->user_can_access)
        <li>
            <a class="nav-main-link {{ \Request::is(explode(',',$menu->active_menu_url))|| $menu->getProperty('always_active',false,'boolean')?'active':'' }}"
               href="{{ url($menu->url) }}"
               target="{{ $menu->target??'_self' }}">
                @if($menu->icon)<i class="{{ $menu->icon }} fa-fw nav-main-link-icon"></i>@endif <span
                        class="nav-main-link-name">{{ $menu->name }}</span>
            </a>
        </li>
    @endif
@endforeach