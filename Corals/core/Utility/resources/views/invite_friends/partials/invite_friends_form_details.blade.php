{!! CoralsForm::openForm(null,['url'=>url($resource_url)]) !!}

<div class="row">
    <div class="col-md-7">
        {!! CoralsForm::text('invitation_subject', 'Utility::labels.invite_friends.invitation_subject', true, $invitation_subject??'') !!}
        {!! CoralsForm::textarea('invitation_text', 'Utility::labels.invite_friends.invitation_text', true, $invitation_text??'',['help_text'=>'Utility::labels.invite_friends.invitation_text_help','rows'=>10]) !!}

        {!! generateCopyToClipBoard('name','{name}')  !!}
        <br/>
        {!! generateCopyToClipBoard('accept_link','{accept_link}')  !!}
    </div>

    <div class="col-md-5">
        @include('Corals::key_value',[
        'name'=> 'friends',
        'label'=> [
            'key'=>'Utility::labels.invite_friends.email',
            'value'=>'Utility::labels.invite_friends.name'
        ],
        'key'=>'email',
        'value'=>'name',
        'options'=>[],
        'newPairLabelButton' => Filters::do_filter('add_new_pairs_label_for_invitation', trans('Corals::labels.add_new_pairs')),
        ])
        <div class="form-group">
            <br/>
            <span data-name="friends"></span>
        </div>
        {!! CoralsForm::formButtons() !!}
    </div>
</div>

{!! CoralsForm::closeForm() !!}
