@foreach($menus as $menu)
  @if($menu->hasChildren('active') && $menu->user_can_access)
      <li class="dropdown {{ \Request::is($menu->active_menu_url)?'':'' }}">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"
             role="button" aria-haspopup="true" aria-expanded="false">
              @if($menu->icon)<i class="{{ $menu->icon }} fa-fw"></i>@endif {{ $menu->name }} <span class="caret"></span></a>
          <ul class="dropdown-menu">
              @include('partials.menu.menu_item', ['menus' => $menu->getChildren('active')])
          </ul>
      </li>
  @elseif($menu->user_can_access)
      <li class="{{ \Request::is($menu->active_menu_url)?'active':'' }}">
          <a href="{{ url($menu->url) }}" target="{{ $menu->target??'_self' }}">
              @if($menu->icon)<i class="{{ $menu->icon }} fa-fw"></i>@endif {{ $menu->name }}
          </a>
      </li>
  @endif
@endforeach