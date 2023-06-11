@foreach($menus as $menu_item)
    @if($menu_item->hasChildren())
        <li class="dd-item" data-id="{{ $menu_item->id }}">
            <div class="dd-handle">
                <i class="{{ $menu_item->icon }} fa-fw"></i>
                <strong>{{ $menu_item->name }}</strong>
                @if($menu_item->status === 'inactive')
                    <i class="fa fa-ban fa-fw text-danger"></i>
                @endif
                <span class="pull-right dd-nodrag">{!! $menu_item->present('action') !!}</span>
            </div>
            <ol class="dd-list">
                @include('Menu::menu.menu_item', ['menus'=> $menu_item->getChildren()])
            </ol>
        </li>
    @else
        <li class="dd-item" data-id="{{ $menu_item->id }}">
            <div class="dd-handle">
                <i class="{{ $menu_item->icon }} fa-fw"></i>
                <strong>{{ $menu_item->name }}</strong>
                @if($menu_item->status === 'inactive')
                    <i class="fa fa-ban fa-fw text-danger"></i>
                @endif
                <span class="pull-right dd-nodrag">{!! $menu_item->present('action') !!}</span>
            </div>
        </li>
    @endif
@endforeach