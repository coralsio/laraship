<style>
    .table-fixed-head {
        overflow: auto;
        height: 100px;
    }

    .table-fixed-head thead th {
        position: sticky;
        top: 0;
        z-index: 1;
        background-color: #f1f1f1;
    }
</style>
{!! CoralsForm::openForm(null ,['url'=>'roles/submit-bulk-update',]) !!}
<div class="">
    <table class="table table-striped table-condensed table-fixed-head">
        <thead>
        <tr class="text-center">
            <th>@lang('User::module.role.permission_title_singular')</th>
            @foreach($roles as $role)
                <th>{{$role->label}}</th>
            @endforeach
        </tr>
        </thead>

        @foreach(\Corals\User\Facades\Roles::getPermissionsTree() as $moduleCode => $models)
            @foreach($models as $name => $package)
                <tr>
                    <th colspan="{{1 + $roles->count()}}">{{ $moduleCode }}
                        [{{ Str::title(preg_replace('/[_]/', ' ', $name)) }}]
                    </th>
                </tr>
                @foreach($package as $id => $name)
                    <tr>
                        <td class="pl-4 p-l-10">{{$name}}</td>
                        @foreach($roles as $role)
                            <td class="text-center">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" value="{{$id}}"
                                           name="{{$role->name}}[]"
                                           @if($role->permissions->pluck('id')->contains($id))
                                               checked
                                            @endif>
                                    <label class="form-check-label"> </label>
                                </div>

                            </td>
                        @endforeach
                    </tr>
                @endforeach
            @endforeach
        @endforeach
    </table>
</div>

{!! CoralsForm::formButtons(trans("Corals::labels.submit"),[],['show_cancel'=>false]) !!}

{!! CoralsForm::closeForm(null) !!}
