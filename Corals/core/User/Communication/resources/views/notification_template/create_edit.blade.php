@extends('layouts.crud.create_edit')



@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('notification_template_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-12">
            @component('components.box')
                {!! CoralsForm::openForm($notification_template) !!}
                <div class="row m-b-20 mb-3">
                    <div class="col-md-4">
                        {!! CoralsForm::text('name', 'Notification::attributes.notification_template.name', false, $notification_template->friendly_name, ['readonly'=>'readonly', 'disabled']) !!}
                        {!! CoralsForm::text('title', 'Notification::attributes.notification_template.title' , true, $notification_template->title, ['help_text' => 'Notification::attributes.notification_template.title_help' ]) !!}

                        {!! CoralsForm::radio('status',trans('Corals::attributes.status'), true, trans('Corals::attributes.status_options')) !!}

                    </div>
                    <div class="col-md-4">
                        {!! CoralsForm::checkboxes('via[]', 'Notification::attributes.notification_template.via' ,true,
                        $options = get_array_key_translation(array_merge(config('notification.supported_channels'), config('notification.user_preferences_options'))),
                        $selected = $notification_template->via ?? [],
                        ['help_text' =>  'Notification::attributes.notification_template.via_help' ]) !!}

                        {!! CoralsForm::select("role_ids[]",  'Notification::attributes.notification_template.roles' , \Roles::getRolesList(['all' => true]), false, $notification_template->roles,
                         ['multiple', 'help_text' => 'Notification::attributes.notification_template.roles_help'], 'select2') !!}
                    </div>
                    <div class="col-md-4">
                        {!! CoralsForm::select("extras[bcc_roles][]",  'Notification::attributes.notification_template.bcc_roles' , \Roles::getRolesList(['all' => true]), false, $notification_template->extras['bcc_roles']??[],
                        ['multiple', 'help_text' => 'Notification::attributes.notification_template.bcc_roles_help'], 'select2') !!}

                        {!! CoralsForm::select("extras[bcc_users][]",  'Notification::attributes.notification_template.bcc_users' , [], false, $notification_template->extras['bcc_users']??[],
                         ['multiple', 'help_text' => 'Notification::attributes.notification_template.bcc_users_help',
                         'class'=>'select2-ajax',
                         'data'=>[
                                'model'=>\Corals\User\Models\User::class,
                                'columns'=> json_encode(['name', 'email']),
                                'selected'=>json_encode($notification_template->extras['bcc_users']??[]),
                                'where'=>json_encode([]),]
                                ], 'select2') !!}

                        @foreach(config('notification.supported_custom_channels', []) as $key => $channel)
                            @php
                                $values = $notification_template->extras['custom'][$key]??[];
                                $values = !empty($values) ? array_combine($values, $values):[]
                            @endphp

                            {!! CoralsForm::select('extras[custom]['.$key.'][]',trans('Notification::labels.to_custom',['channel'=> trans($channel)]),$values,false,$values,['class'=>'tags','multiple'=>true], 'select2') !!}
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        {{ CoralsForm::label('body', trans('Notification::attributes.notification_template.body')) }}

                        <ul class="nav nav-tabs nav-primary">
                            @foreach(config('notification.supported_channels') as $channelKey => $channelName)
                                <li class="nav-item {{$loop->first ? 'active' : ''}}">
                                    <a href="#{{$channelKey}}" data-toggle="tab"
                                       class="{{$loop->first ? 'active' : ''}} nav-link"
                                       aria-expanded="true">@lang($channelName)</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach(config('notification.supported_channels') as $channelKey => $channelName)
                                <div class="tab-pane {{$loop->first ? 'active' : ''}}" id="{{$channelKey}}">
                                    @if($channelKey === "mail")
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! CoralsForm::text('email_from', 'Notification::attributes.notification_template.email_from', false, $notification_template->email_from,[
                                                    'help_text' =>  'Notification::attributes.notification_template.email_from_help'
                                                    ]) !!}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-12">
                                            {!! CoralsForm::textarea("body[$channelKey]", $channelName, true,
                                            (($notification_template->exists && $body && array_key_exists($channelKey, $body)) ? $body[$channelKey] : ''),
                                             ($channelKey == "nexmo" ?[] :   ['class'=>'ckeditor'])) !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-4">
                        @if(sizeof($notificationParametersDescription) > 0)
                            {{ CoralsForm::label('notification_parameters', trans('Notification::labels.notification_parameters')) }}
                            <small class="help-block text-muted">@lang('Notification::labels.notification_parameters_help')</small>
                            <table class="table color-table info-table table table-hover table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th>@lang('Notification::labels.parameter')</th>
                                    <th>@lang('Notification::labels.description')</th>
                                </tr>
                                </thead>
                                @foreach($notificationParametersDescription as $parameterName => $description )
                                    <tbody>
                                    <tr>
                                        <td><b id="shortcode_{{ $parameterName }}">{{ '{'.$parameterName.'}' }}</b>
                                            <a href="#" onclick="event.preventDefault();" class="copy-button"
                                               data-clipboard-target="#shortcode_{{ $parameterName }}"><i
                                                        class="fa fa-clipboard"></i></a></td>
                                        <td>@lang($description)</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::formButtons() !!}
                    </div>
                </div>
                {!! CoralsForm::closeForm($notification_template) !!}
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
    </script>
@endsection
