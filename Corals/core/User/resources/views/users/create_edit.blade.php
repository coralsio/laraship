@extends('layouts.crud.create_edit')


@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('user_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-12">
            @component('components.box')
                {!! CoralsForm::openForm($user, ['files'=>true]) !!}
                <div class="row">
                    <div class="col-md-4">
                        {!! CoralsForm::text('name', 'User::attributes.user.name', true) !!}
                        {!! CoralsForm::email('email', 'User::attributes.user.email', true) !!}
                        {!! CoralsForm::radio('status','Corals::attributes.status', true, trans('Corals::attributes.status_options')) !!}

                    @if ((\Settings::get('confirm_user_registration_email', false)))
                            {!! CoralsForm::checkbox('confirmed', 'User::attributes.user.confirmed', $user->confirmed) !!}
                        @endif

                        {!! CoralsForm::password('password','User::attributes.user.password', false, ['help_text'=>!$user->exists?trans('User::attributes.user.password_help'):'']) !!}
                        {!! CoralsForm::password('password_confirmation', 'User::attributes.user.password_confirmation') !!}

                        {!! CoralsForm::checkbox('properties[force_reset]', 'User::attributes.user.force_reset', $user->getProperty('force_reset')) !!}

                        @if(!$user->exists)
                            {!! CoralsForm::checkbox('send_login_details', 'User::attributes.user.send_login_details') !!}
                        @endif

                        @if(\TwoFactorAuth::isActive())
                            {!! CoralsForm::checkbox('two_factor_auth_enabled', 'User::attributes.user.two_factor_auth_enabled' ,\TwoFactorAuth::isEnabled($user)) !!}

                            @if(!empty(\TwoFactorAuth::getSupportedChannels()))
                                {!! CoralsForm::radio('channel', 'User::attributes.user.channel' , false,\TwoFactorAuth::getSupportedChannels(),\Arr::get($user->getTwoFactorAuthProviderOptions(),'channel', null)) !!}
                            @endif
                        @endif
                    </div>
                    <div id="country-div" class="col-md-4">
                        {!! CoralsForm::text('last_name', 'User::attributes.user.last_name', true) !!}

                        {!! CoralsForm::text('job_title', 'User::attributes.user.job_title' ) !!}

                        {!! CoralsForm::text('phone_country_code', 'User::attributes.user.phone_country_code' ,false,null,['id'=>'authy-countries']) !!}
                        {!! CoralsForm::text('phone_number', 'User::attributes.user.phone_number' ,false,null,['id'=>'authy-cellphone']) !!}
                        {!! CoralsForm::select('classification','User::attributes.user.classification', \Settings::get('customer_classifications',[])) !!}

                        {!! CoralsForm::checkboxes('roles[]', 'User::attributes.user.roles' ,true,\Roles::getRolesListForLoggedInUser() ?? [],$user->roles->pluck('id')->toArray()) !!}

                        @if($groups =\Users::getGroupsList())
                            {!! CoralsForm::checkboxes('groups[]', 'User::module.group.title' ,false, $groups, $user->groups->pluck('id')->toArray()) !!}
                        @endif

                    </div>
                    <div class="col-md-4">
                        {!! CoralsForm::file('picture',  'User::attributes.user.picture' ) !!}

                        <img src="{{ $user->picture_thumb }}" class="img-circle img-responsive" width="150"
                             alt="User Picture"/>
                        @if($user->exists && $user->getFirstMedia('user-picture'))
                            {!! CoralsForm::checkbox('clear',  'User::attributes.user.default_picture' ) !!}
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        {!! CoralsForm::textarea('properties[about]', 'User::attributes.user.about' , false, null,[
                        'class'=>'limited-text',
                        'maxlength'=>250,
                        'help_text'=>'<span class="limit-counter">0</span>/250',
                        'rows'=>'4']) !!}
                    </div>
                </div>
                {!! CoralsForm::customFields($user) !!}

                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::formButtons() !!}
                    </div>
                </div>

                {!! CoralsForm::closeForm($user) !!}
            @endcomponent
        </div>
    </div>
@endsection


