<div class="row">
    <div class="col-md-12">
        @component('components.box')
            @slot('box_title')
                @if($root)
                    @lang('Menu::labels.root_menu_title',['title'=>$menu->name])
                @elseif($parent)
                    @lang('Menu::labels.parent_menu_title',['title'=> $parent->name])
                @else
                    @lang('Menu::labels.menu_item_title',['title'=>$menu->name])
                @endif
            @endslot

            @slot('box_actions')
                @if($menu->exists && $root)
                    {!! CoralsForm::link(url(config('menu.models.menu.resource_url') . '/create?parent=' . $menu->hashed_id),trans('Corals::labels.create'),
                    ['class'=>'btn btn-sm btn-success','data' => ['action' => 'load','load_to' => '#menu_form']]) !!}
                @endif
            @endslot

            {!! CoralsForm::openForm($menu,['url' => url(config('menu.models.menu.resource_url').'/'.$menu->hashed_id), 'data-page_action'=>'site_reload']) !!}
            {{ CoralsForm::hidden('parent_id', $menu->parent_id) }}
            {{ CoralsForm::hidden('root', $root) }}

            @if($root)
                {!! CoralsForm::text('key','Menu::attributes.menu.key',true) !!}

                {!! CoralsForm::text('name','Menu::attributes.menu.name',true) !!}

                {!! CoralsForm::radio('status','Corals::attributes.status', true, trans('Corals::attributes.status_options')) !!}

                {!! CoralsForm::textarea('description','Menu::attributes.menu.description',false,$menu->description,['rows'=>3]) !!}

            @else
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item active">
                            <a class="nav-link active" id="menu-item-tab" data-toggle="tab" href="#menu-item" role="tab" aria-controls="menu-item" aria-selected="true">Menu Item</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="permission-tab" data-toggle="tab" href="#permission" role="tab" aria-controls="permission" aria-selected="false">Permission</a>
                        </li>
                    </ul>
            @endif

            @if(!$root)
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane active" id="menu-item" role="tabpanel" aria-labelledby="menu-item-tab">
                        {!! CoralsForm::text('name','Menu::attributes.menu.name',true) !!}

                        {!! CoralsForm::radio('status','Corals::attributes.status', true, trans('Corals::attributes.status_options')) !!}

                        {!! CoralsForm::text('url','Menu::attributes.menu.url') !!}

                        {!! CoralsForm::text('active_menu_url','Menu::attributes.menu.active_menu_url',false,$menu->active_menu_url,['help_text'=> 'Menu::attributes.menu.active_menu_url_help']) !!}

                        {!! CoralsForm::text('icon','Menu::attributes.menu.icon',false,str_replace('fa ','',$menu->icon),['class'=>'icp icp-auto',
                        'help_text'=>'Menu::attributes.menu.icon_help']) !!}

                        {!! CoralsForm::select('target', trans('Menu::attributes.menu.target'),trans('Menu::attributes.menu.target_options')) !!}

                        {!! CoralsForm::select('roles[]',trans('Menu::attributes.menu.roles'), \Corals\User\Facades\Roles::getRolesList(),false,null,
                        ['class'=>'','multiple'=>true,
                        'help_text'=>'Menu::attributes.menu.roles_help'],'select2') !!}

                        {!! CoralsForm::checkbox('properties[always_active]', 'Menu::attributes.menu.always_active', $menu->properties['always_active']??false, 'True') !!}

                        {!! CoralsForm::textarea('description','Menu::attributes.menu.description',false,$menu->description,['rows'=>3]) !!}
                    </div>
                    <div class="tab-pane" id="permission" role="tabpanel" aria-labelledby="permission-tab">
                        <div class="row">
                            <div class="col-md-12 permissions">
                                <div class="text-right">
                                    {!! CoralsForm::button( 'User::labels.toggle_collapse' ,['class'=>'btn btn-sm btn-primary','id'=>'toggle_collapse']) !!}
                                    {!! CoralsForm::button( 'User::labels.check_all' ,['class'=>'btn btn-sm btn-success','id'=>'check_all']) !!}
                                    {!! CoralsForm::button( 'User::labels.revoke_all' ,['class'=>'btn btn-sm btn-warning','id'=>'revoke_all']) !!}
                                    <hr/>
                                </div>
                                <div class="">
                                    <small class="text-muted">
                                        <i class="fa fa-th-large"></i> @lang('User::labels.package')
                                    </small>
                                    <small class="text-muted m-l-10">
                                        <i class="fa fa-square"></i> @lang('User::labels.model')
                                    </small>
                                    <hr/>
                                </div>
                                @foreach(\Corals\User\Facades\Roles::getPermissionsTree() as $name => $package)
                                    <ul class="list-unstyled panel-group" id="{{ $name }}_accordion">
                                        <li>
                                            <i class="fa fa-th-large"></i> {{ $name }}
                                            <ul class="list-unstyled" style="margin-left: 25px;">
                                                @foreach($package as $name => $model)
                                                    <li>
                                                        <a data-toggle="collapse"
                                                           data-parent="#collapse_{{ $colID = $name.\Str::random() }}"
                                                           href="#collapse_{{ $colID }}">
                                                            <i class="fa fa-square"></i> {{ $name }}</a>
                                                        <ul class="list-inline panel-collapse collapse"
                                                            id="collapse_{{ $colID }}"
                                                            style="margin-left: 25px;">
                                                            @foreach($model as $id => $name)
                                                                <li>
                                                                    {!! CoralsForm::checkbox('permissions[]',$name,$menu->permissions->pluck('id')->contains($id),$id,['id'=>'perm_'.$id]) !!}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    </ul>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            @endif
            {!! CoralsForm::customFields($menu,'col-md-12') !!}

            {!! CoralsForm::formButtons(trans('Corals::labels.save', ['title'=> $title_singular]), [], ['href' => url('menus')])  !!}

            {!! CoralsForm::closeForm($menu) !!}
        @endcomponent
    </div>
</div>
