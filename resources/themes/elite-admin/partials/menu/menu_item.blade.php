@foreach($menus as $menu)
    @if($menu->hasChildren('active') && $menu->user_can_access)
        <li class="{{ \Request::is(explode(',',$menu->active_menu_url))|| $menu->getProperty('always_active',false,'boolean')?'active':'' }}">
            <a class="has-arrow waves-effect waves-dark"
               href="javascript:void(0)" aria-expanded="false">
                {!! $menu->icon?'<i class="'. $menu->icon .' fa-fw"></i> ':'' !!} <span
                        class="hide-menu">{{ $menu->name }}</span>
            </a>
            <ul aria-expanded="false" class="collapse">
                @include('partials.menu.menu_item', ['menus'=>$menu->getChildren('active'), 'is_sub'=>true])
            </ul>
        </li>
    @elseif($menu->user_can_access)
        @if(isset($is_sub) && $is_sub)
            <li class="{{ \Request::is(explode(',',$menu->active_menu_url))|| $menu->getProperty('always_active',false,'boolean')?'active':'' }}">
                <a href="{{ url($menu->url) }}" target="{{ $menu->target??'_self' }}">
                    {!! $menu->icon?'<i class="'. $menu->icon .' fa-fw"></i> ':'' !!} {{ $menu->name }}
                </a>
            </li>
        @else
            <li class="{{ \Request::is(explode(',',$menu->active_menu_url))|| $menu->getProperty('always_active',false,'boolean')?'active':'' }}">
                <a class="waves-effect waves-dark" href="{{ url($menu->url) }}" target="{{ $menu->target??'_self' }}">
                    {!! $menu->icon?'<i class="'. $menu->icon .' fa-fw"></i> ':'' !!} <span>{{ $menu->name }}</span>
                </a>
            </li>
        @endif
    @endif
@endforeach