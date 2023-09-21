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
    {!! Html::style('assets/corals/plugins/cropper/cropper.css') !!}
    {!! Html::style('assets/corals/plugins/authy/flags.authy.css') !!}
    {!! Html::style('assets/corals/plugins/authy/form.authy.css') !!}
    <style>
        #image_source {
            cursor: pointer;
        }

        .modal-footer {
            display: block !important;
        }

        .profile-user-img {
            margin: 0 auto;
            width: 100px;
            padding: 3px;
            border: 3px solid #d2d6de;
            cursor: pointer;
        }

        .cropper-bg{
            width: 100% !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <!-- /.col -->
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs nav-tabs-block js-tabs-enabled">
                    <li class="nav-item">
                        <a href="#profile" data-toggle="tab" class="nav-link @if($active_tab=="profile") active @endif">
                            @lang('corals-one-ui::labels.auth.profile')
                        </a>
                    </li>
                    @php \Actions::do_action('user_profile_tabs',user(),$active_tab) @endphp

                </ul>
                <div class="block-content tab-content" style="background: white">
                    <div class="tab-pane @if($active_tab=="profile") active @endif" id="profile">
                        {!! CoralsForm::openForm($user = user(), ['url' => url('profile'), 'method'=>'PUT','class'=>'ajax-form','files'=>true]) !!}
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs nav-tabs-block js-tabs-enabled">
                                <li class="nav-item">
                                    <a href="#edit_profile" data-toggle="pill" class="nav-link active">
                                        <i class="fa fa-pencil"></i> @lang('corals-one-ui::labels.auth.edit_profile')
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#profile_addresses" class="nav-link" data-toggle="pill"><i
                                                class="fa fa-map-marker"></i>
                                        @lang('corals-one-ui::labels.auth.addresses')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#reset_password" class="nav-link" data-toggle="pill"><i
                                                class="fa fa-lock"></i>
                                        @lang('corals-one-ui::labels.auth.auth_password')</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#notification_preferences" class="nav-link"
                                       data-toggle="pill">
                                        <i class="fa fa-bell-o"></i>
                                        @lang('corals-one-ui::labels.auth.notification_preferences')</a>
                                </li>
                            </ul>
                            <div class="block-content tab-content" style="background: white">
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
                                                <small class="d-block">@lang('corals-one-ui::labels.auth.click_pic_update')</small>
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
                                                ['checkboxes_wrapper'=>'span', 'label'=>['class' => 'm-r-10 w-200','style'=>'margin-right:10px']])
                                                !!}
                                            </div>
                                        </div>
                                    @empty
                                        <h4>@lang('corals-one-ui::labels.auth.no_notification')</h4>
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
                                {!! CoralsForm::formButtons(trans('corals-one-ui::labels.auth.save',['title' => $title_singular]),[],['href'=>url('dashboard')]) !!}
                            </div>
                        </div>
                        {!! CoralsForm::closeForm() !!}
                    </div>
                    @php \Actions::do_action('user_profile_tabs_content',user(),$active_tab) @endphp

                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
    </div>
    <!-- /.row -->
    <!-- /.row -->
    <div class="modal fade modal-image" id="modal-image-crop" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img width="100%" src="" id="image_cropper" alt="picture 1" class="img-responsive img-fluid">
                </div>
                <div class="modal-footer ">
                    <div class="row">
                        <div class="file col-md-6">
                            <div class="text-left">
    <span class="btn btn-info btn-file">
    @lang('corals-one-ui::labels.auth.browse_files')
        <input type="file" class="custom-file" id="cropper"
               onchange="document.getElementById('uploader').value = this.value;" required>

    </span>

                            </div>
                        </div>
                        <div class="col-md-6 " style="margin-bottom: 10px">
                            <button type="button" class="btn btn-primary rotate pull-right" data-method="rotate"
                                    data-option="-30">
                                <i class="fa fa-undo"></i></button>
                            <button type="button" class="btn btn-primary rotate pull-right"
                                    style="margin-right: 10px" data-method="rotate"
                                    data-option="30">
                                <i class="fa fa-repeat"></i></button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"
                            style="margin: 0 10px 0 18px"
                            id="Save">@lang('corals-one-ui::labels.auth.save',['title'=>''])</button>
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">@lang('corals-one-ui::labels.auth.close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    {!! Html::script('assets/corals/plugins/cropper/cropper.js') !!}
    {!! \Html::script('assets/corals/plugins/authy/form.authy.js') !!}

    <script type="text/javascript">
        $('#country-div').on("DOMSubtreeModified", function () {
            $(".countries-input").addClass('form-control');
        });

        $(function () {
/////// Cropper Options set
            var $cropper = $('#image_cropper');
            var options = {
                aspectRatio: 1 / 1,
                minContainerWidth: 570,
                minContainerHeight: 350,
                minCropBoxWidth: 145,
                minCropBoxHeight: 145,
                rotatable: true,
                cropBoxResizable: true,
                crop: function (e) {
                    $("#cropped_value").val(JSON.stringify(e.detail));
                }
            };
///// Show cropper on existing Image
            $("body").on("click", "#image_source", function () {
                var src = $("#image_source").attr("src");
                src = src.replace("/thumb", "");
                $cropper.attr('src', src);
                $("#modal-image-crop").modal("show");
            });
///// Destroy Cropper on Model Hide
            $("#modal-image-crop").on("hide.bs.modal", function () {
                $cropper.cropper('destroy');
                $(".cropper-container").remove();

            });
/// Show Cropper on Model Show
            $("#modal-image-crop").on("show.bs.modal", function () {
                $cropper.cropper(options);
            });
///// Rotate Image
            $("body").on("click", "#modal-image-crop .rotate", function () {
                var degree = $(this).attr("data-option");
                $cropper.cropper('rotate', degree);
            });
///// Saving Image with Ajax Call
            $("body").on("click", "#Save", function () {
                var cropped_image = $cropper.cropper('getCroppedCanvas');
                var canvasURL = cropped_image.toDataURL('image/jpeg');
                $("#image_source").attr('src', canvasURL);
                $("input[name=profile_image]").val(canvasURL);

                $cropper.cropper('destroy');
                $("#modal-image-crop").modal("hide");
            });

////// When user upload image
            $(document).on("change", "#cropper", function () {
                var imagecheck = $(this).data('imagecheck'),
                    file = this.files[0],
                    imagefile = file.type,
                    _URL = window.URL || window.webkitURL;
                img = new Image();
                img.src = _URL.createObjectURL(file);
                img.onload = function () {
                    var match = ["image/jpeg", "image/png", "image/jpg"];
                    if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
                        alert('Please Select A valid Image File');
                        return false;
                    } else {
                        var reader = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onloadend = function () { // set image data as background of div
                            $('#image_cropper').attr('src', this.result);
                            $cropper.cropper('destroy');
                            $cropper.cropper(options);
                        }
                    }
                }
            });
        });
    </script>

    <script type="text/javascript">
        function refresh_address(data) {
            $('#profile_addresses').html(data.address_list);
            $('#profile_addresses input').val("");
            $('#profile_addresses select').val("");
        }
    </script>
@endsection
