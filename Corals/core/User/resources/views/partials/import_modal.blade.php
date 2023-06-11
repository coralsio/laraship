<style>
    .import-description-table .table {
        font-size: small;
    }

    .import-description-table .table > tbody > tr > td {
        padding: 4px;
    }

    .required-asterisk {
        color: red;
        font-size: 100%;
        top: -.4em;
    }
</style>

<div>
    {!! CoralsForm::openForm(null, ['url' => url('import/'.$resource_url.'/upload-import-file'), 'files' => true]) !!}
    {!! CoralsForm::file('file', 'User::import.labels.file') !!}

    {!! CoralsForm::checkboxes('roles[]', 'User::attributes.user.roles' ,true,\Roles::getRolesList(),null)!!}

    @if($groups = \Users::getGroupsList())
        {!! CoralsForm::checkboxes('groups[]', 'User::module.group.title' ,false, $groups, null) !!}
    @endif

    {!! CoralsForm::formButtons('User::import.labels.upload_file', [], ['show_cancel' => false]) !!}
    {!! CoralsForm::closeForm() !!}

    {!! CoralsForm::link(url('import/'.$resource_url.'/download-import-sample'),
    trans('User::import.labels.download_sample'),
    ['class' => '']) !!}
</div>
<hr/>
<h4>@lang('User::import.labels.column_description')</h4>
<div class="table-responsive import-description-table">
    <table class="table table-striped">
        <thead>
        <tr>
            <th style="width: 120px;">@lang('User::import.labels.column')</th>
            <th>@lang('User::import.labels.description')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($headers as $column => $description)
            <tr>
                <td>{{ $column }}</td>
                <td>{!! $description !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script>
    initSelect2ajax();
</script>
