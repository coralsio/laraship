@extends('layouts.master')

@section('title',$title)

@section('css')
    {!! \Html::style('assets/corals/plugins/nestable/nestable.css') !!}
    {!! \Html::style('assets/corals/plugins/fontawesome-iconpicker/css/fontawesome-iconpicker.min.css') !!}
@endsection
@section('content_header')
    @component('components.content_header')

        @slot('page_title')
            {{ $title }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('menu') }}
        @endslot

    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            @component('components.box')
                @include('components.nav_pills',['pills'=>\Corals\Menu\Facades\Menus::getParents($root->key)])

                <div class="dd" id="menu_tree">
                    <ol class="dd-list">
                        @include('Menu::menu.menu_item', ['menus'=>\Menus::getMenu($root->key) ])
                    </ol>
                </div>
            @endcomponent
        </div>
        <div class="col-md-8">
            <div id="menu_form">
                @include('Menu::menu.create_edit', ['menu' => $root, 'root' => true,'parent'=>null])
            </div>
        </div>
    </div>
@endsection

@section('js')
    {!! \Html::script('assets/corals/plugins/nestable/jquery.nestable.js') !!}
    {!! \Html::script('assets/corals/plugins/fontawesome-iconpicker/js/fontawesome-iconpicker.min.js') !!}

    <script type="text/javascript">
        var original_tree = '';

        var updateMenu = function (e) {
            var tree = $(e.target).nestable('serialize');
            tree = JSON.stringify(tree);

            if (_.isEqual(original_tree, tree)) {
                return;
            }

            var formData = new FormData();
            formData.append('tree', tree);

            var url = '{!! url(config('menu.models.menu.resource_url').'/update-tree/'.$root->hashed_id) !!}';

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (data, textStatus, jqXHR) {
                    original_tree = JSON.stringify($('#menu_tree').nestable('serialize'));
                    themeNotify(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    themeNotify(data);
                }
            });
        };

        var nestable = $('#menu_tree').nestable();

        nestable.nestable('collapseAll');

        nestable.on('change', updateMenu);

        original_tree = JSON.stringify($('#menu_tree').nestable('serialize'));
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $(document).on("click","#check_all", function (e) {
                let $permissionInput = $('.permissions input');
                $permissionInput.prop('checked', true);
                $permissionInput.trigger('change');
            });
            $(document).on("click",'#revoke_all',function (e) {
                let $permissionInput = $('.permissions input');

                $permissionInput.prop('checked', false);
                $permissionInput.trigger('change');
            });

            $(document).on("click",'#toggle_collapse',function (e) {
                $('.panel-collapse').collapse('toggle');
            });
        })
    </script>
@endsection