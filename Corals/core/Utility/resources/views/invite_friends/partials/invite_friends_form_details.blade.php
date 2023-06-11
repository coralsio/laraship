{!! CoralsForm::openForm(null,['url'=>url($resource_url)]) !!}

<div class="row">
    <div class="col-md-4">
        {!! CoralsForm::text('invitation_subject', 'Utility::labels.invite_friends.invitation_subject', true, $invitation_subject??'') !!}
        {!! CoralsForm::textarea('invitation_text', 'Utility::labels.invite_friends.invitation_text', true, $invitation_text??'',['help_text'=>'Utility::labels.invite_friends.invitation_text_help']) !!}

        {!! generateCopyToClipBoard('name','{name}')  !!}
    </div>

    <div class="col-md-8">
        @include('Corals::key_value',[
        'name'=> 'friends',
        'label'=> [
            'key'=>'Utility::labels.invite_friends.email',
            'value'=>'Utility::labels.invite_friends.name'
        ],
        'key'=>'email',
        'value'=>'name',
        'options'=>[]
        ])
        <div class="form-group">
            <br/>
            <span data-name="friends"></span>
        </div>
        {!! CoralsForm::formButtons() !!}
    </div>
</div>

{!! CoralsForm::closeForm() !!}
