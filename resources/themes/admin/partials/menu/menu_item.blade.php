@foreach($menus as $menu)
    @if($menu->hasChildren('active') && $menu->user_can_access)
        <li class="treeview {{ \Request::is(explode(',',$menu->active_menu_url))|| $menu->getProperty('always_active',false,'boolean') ?'active menu-open':'' }}">
            <a href="#">
                @if($menu->icon)<i class="{{ $menu->icon }} fa-fw"></i>@endif <span>{{ $menu->name }}</span>
                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                @include('partials.menu.menu_item', ['menus'=>$menu->getChildren('active')])
            </ul>
        </li>
    @elseif($menu->user_can_access)
        <li class="{{ \Request::is(explode(',',$menu->active_menu_url))|| $menu->getProperty('always_active',false,'boolean')?'active':'' }}">
            <a href="{{ url($menu->url) }}" target="{{ $menu->target??'_self' }}">
                @if($menu->icon)<i class="{{ $menu->icon }} fa-fw"></i>@endif <span>{{ $menu->name }}</span>
            </a>
        </li>
    @endif
@endforeach