@extends('layouts.master')

@section('title',$title)

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('profile') }}
        @endslot
    @endcomponent
@endsection

@section('css')
    {!! \Html::style('assets/corals/plugins/cropper/cropper.css') !!}
    <style>
        #image_source {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    @component('components.box')
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a href="#profile" class="nav-link {{ $active_tab=="profile"? 'active':'' }}"
                           role="tab" data-toggle="tab">
                            @lang('corals-elite-admin::labels.auth.profile')
                        </a>
                    </li>
                    @php \Actions::do_action('user_profile_tabs',user(),$active_tab) @endphp
                </ul>
                <div class="tab-content">
                    <div class="tab-pane {{ $active_tab=="profile"? 'active':'' }}" id="profile" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                {!! CoralsForm::openForm($user = user(), ['url' => url('profile'), 'method'=>'PUT','class'=>'ajax-form','files'=>true]) !!}
                                <ul class="nav nav-pills mt-2">
                                    <li class="nav-item"><a href="#edit_profile" class="nav-link active"
                                                            data-toggle="tab"
                                                            aria-expanded="false">
                                            <i class="fa fa-pencil"></i> @lang('corals-elite-admin::labels.auth.edit_profile')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#profile_addresses" class="nav-link" data-toggle="tab"><i
                                                    class="fa fa-map-marker"></i>
                                            @lang('corals-elite-admin::labels.auth.addresses')</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#reset_password" class="nav-link" data-toggle="tab"><i
                                                    class="fa fa-lock"></i>
                                            @lang('corals-elite-admin::labels.auth.auth_password')</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#notification_preferences" class="nav-link" data-toggle="tab"><i
                                                    class="fa fa-bell-o"></i>
                                            @lang('corals-elite-admin::labels.auth.notification_preferences')</a>
                                    </li>
                                </ul>
                                <div class="tab-content py-3">
                                    <div class="tab-pane active" id="edit_profile">
                                        <div class="row">
                                            <div class="col-md-4">
                                                {!! CoralsForm::text('name','User::attributes.user.name',true) !!}
                                                {!! CoralsForm::email('email','User::attributes.user.email',true) !!}
                                                {!! CoralsForm::textarea('properties[about]', 'User::attributes.user.about' , false, null,[
                                                'class'=>'limited-text',
                                                'maxlength'=>250,
                                                'help_text'=>'<span class="limit-counter">0</span>/250',
                                                'rows'=>'4']) !!}
                                            </div>
                                            <div id="country-div" class="col-md-4">

                                                {!! CoralsForm::text('last_name','User::attributes.user.last_name',true) !!}

                                                {!! CoralsForm::text('phone_country_code','User::attributes.user.phone_country_code',false,null,['id'=>'authy-countries']) !!}
                                                {!! CoralsForm::text('phone_number','User::attributes.user.phone_number',false,null,['id'=>'authy-cellphone']) !!}
                                                {!! CoralsForm::text('job_title','User::attributes.user.job_title') !!}
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <img id="image_source"
                                                         class="profile-user-img img-responsive img-circle"
                                                         style="width: 200px"
                                                         src="{{ user()->picture }}"
                                                         alt="User profile picture">
                                                    {{ CoralsForm::hidden('profile_image') }}
                                                    <small class="">@lang('corals-elite-admin::labels.auth.click_pic_update')</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="profile_addresses">
                                        @include('Settings::addresses.address_list_form', [
                                        'url'=>url('users/'.$user->hashed_id.'/address'),'method'=>'POST',
                                        'model'=>$user,
                                        'addressDiv'=>'#profile_addresses'
                                        ])
                                    </div>
                                    <div class="tab-pane" id="notification_preferences">
                                        @forelse(\CoralsNotification::getUserNotificationTemplates(user()) as $notifications_template)
                                            <div class="row">
                                                <div class="col-md-12">
                                                    {!! CoralsForm::checkboxes(
                                                    'notification_preferences['.$notifications_template->id .'][]',
                                                    $notifications_template->friendly_name,
                                                    false, $options = get_array_key_translation(config('notification.supported_channels')),
                                                    $selected = $user->notification_preferences[$notifications_template->id] ?? [],
                                                    ['checkboxes_wrapper'=>'span', 'label'=>['class' => 'm-r-10']])
                                                    !!}
                                                </div>
                                            </div>
                                        @empty
                                            <h4>@lang('corals-elite-admin::labels.auth.no_notification')</h4>
                                        @endforelse
                                    </div>
                                    <div class="tab-pane" id="reset_password">
                                        <div class="row">
                                            <div class="col-md-4">
                                                {!! CoralsForm::password('password','User::attributes.user.password') !!}
                                                {!! CoralsForm::password('password_confirmation','User::attributes.user.password_confirmation') !!}

                                                @if(\TwoFactorAuth::isActive())
                                                    {!! CoralsForm::checkbox('two_factor_auth_enabled','User::attributes.user.two_factor_auth_enabled',\TwoFactorAuth::isEnabled($user)) !!}

                                                    @if(!empty(\TwoFactorAuth::getSupportedChannels()))
                                                        {!! CoralsForm::radio('channel','User::attributes.user.channel', false,\TwoFactorAuth::getSupportedChannels(),\Arr::get($user->getTwoFactorAuthProviderOptions(),'channel', null)) !!}
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="col-md-6 text-center">
                                                <i class="fa fa-lock" style="color:#7777770f; font-size: 10em;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    {!! CoralsForm::formButtons(trans('corals-elite-admin::labels.auth.save',['title' => $title_singular]),[],['href'=>url('dashboard')]) !!}
                                </div>
                                {!! CoralsForm::closeForm() !!}
                            </div>
                        </div>
                    </div>
                    @php \Actions::do_action('user_profile_tabs_content',user(),$active_tab) @endphp
                </div>
            </div>
        </div>
    @endcomponent

    @include('User::users.profile.cropper_modal')

@endsection
@section('js')
    @include('User::users.profile.scripts')
@endsection