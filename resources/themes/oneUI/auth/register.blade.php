@extends('layouts.auth')

@section('title',trans('corals-admin::labels.auth.register'))
@section('css')
    {!! Html::style('assets/corals/plugins/authy/flags.authy.css') !!}
    {!! Html::style('assets/corals/plugins/authy/form.authy.css') !!}
    <style type="text/css">
        .login-box, .register-box {
            width: 720px;
            margin: 6% auto;
        }

        @media (max-width: 470px) {
            .login-box, .register-box {
                width: 340px;
            }
        }

        #terms {
            color: black;
        }

        #terms-anchor {
            text-transform: uppercase;
        }
    </style>
@endsection
@section('content')
    <h4 class="login-box-msg">@lang('corals-admin::labels.auth.register_new_account')</h4>

    <form method="POST" action="{{ route('register') }}" class="ajax-form">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-3 p-r-0">
                <div class="form-group has-feedback {{ $errors->has('name') ? ' has-error' : '' }}">
                    <input type="text" name="name"
                           class="form-control" placeholder="@lang('User::attributes.user.name')"
                           value="{{ old('name') }}" autofocus/>
                    <span class="glyphicon glyphicon-user form-control-icon"></span>

                    @if ($errors->has('name'))
                        <div class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-3" style="padding-left:2px">
                <div class="form-group has-feedback {{ $errors->has('last_name') ? ' has-error' : '' }}">
                    <input type="text" name="last_name"
                           class="form-control" placeholder="@lang('User::attributes.user.last_name')"
                           value="{{ old('last_name') }}" autofocus/>
                    <span class="glyphicon glyphicon-user form-control-icon"></span>

                    @if ($errors->has('last_name'))
                        <div class="help-block">
                            <strong>{{ $errors->first('last_name') }}</strong>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" name="email"
                           class="form-control" placeholder="@lang('User::attributes.user.email')"
                           value="{{ old('email') }}"/>
                    <span class="glyphicon glyphicon-envelope form-control-icon"></span>

                    @if ($errors->has('email'))
                        <div class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" name="password" class="form-control"
                           placeholder="@lang('User::attributes.user.password')"/>
                    <span class="glyphicon glyphicon-lock form-control-icon"></span>

                    @if ($errors->has('password'))
                        <div class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="@lang('User::attributes.user.retype_password')"/>
                    <span class="glyphicon glyphicon-lock form-control-icon"></span>

                    @if ($errors->has('password_confirmation'))
                        <div class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if($is_two_factor_auth_enabled = \Settings::get('two_factor_auth_enabled', false))
            <div class="row">
                <div class="col-md-6">
                    <div id="country-div"
                         class="form-group has-feedback {{ $errors->has('phone_country_code') ? ' has-error' : '' }}">
                        <label for="authy-countries" class="control-label">@lang('corals-admin::labels.auth.country')
                            :</label>
                        <select class="form-control" id="authy-countries" name="phone_country_code"></select>
                        <span class="glyphicon glyphicon-flag form-control-feedback"></span>

                    @if ($errors->has('phone_country_code'))
                        <div class="help-block">
                            <strong>{{ $errors->first('phone_country_code') }}</strong>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group has-feedback {{ $errors->has('phone_number') ? ' has-error' : '' }}">
                    <input class="form-control" id="authy-cellphone"
                           placeholder="@lang('User::attributes.user.cell_phone_number')" type="text"
                           value="{{ old('phone_number') }}"
                           name="phone_number"/>
                    <span class="glyphicon glyphicon-phone form-control-icon"></span>

                    @if ($errors->has('phone_number'))
                        <div class="help-block">
                            <strong>{{ $errors->first('phone_number') }}</strong>
                        </div>
                    @endif
                </div>
            </div>
            </div>
        @endif
        <div class="row">
            <div class="col-xs-8">
                <div class="form-group has-feedback {{ $errors->has('terms') ? ' has-error' : '' }}">
                    <div class="checkbox icheck">
                        <label>
                            <input name="terms" value="1" type="checkbox"/>
                            <strong>@lang('corals-admin::labels.auth.agree')
                                <a href="#" data-toggle="modal" id="terms-anchor"
                                   data-target="#terms">@lang('corals-admin::labels.auth.terms')</a>
                            </strong>
                        </label>
                    </div>
                    @if ($errors->has('terms'))
                        <span class="help-block"><strong>@lang('corals-admin::labels.auth.accept_terms')</strong></span>
                    @endif
                </div>
            </div>
            <!-- /.col -->
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3 offset-md-3 my-3">
                <button type="submit"
                        class="btn bg-olive btn-block">@lang('corals-admin::labels.auth.register')</button>
            </div>
        </div>
    </form>
    <br/>
    <div class="row">
        <div class="col-md-6 col-md-offset-3 offset-md-3 my-3 text-center">
            <a class="" href="{{ route('login') }}">@lang('corals-admin::labels.auth.already_have_account')</a><br>
        </div>
    </div>
    @component('components.modal',['id'=>'terms','header'=>\Settings::get('site_name').' Terms and policy'])
        {!! \Settings::get('terms_and_policy') !!}
    @endcomponent
@endsection

@section('js')
    {!! \Html::script('assets/corals/plugins/authy/form.authy.js') !!}
    <script type="text/javascript">
        $('#country-div').on("DOMSubtreeModified", function () {
            $(".countries-input").addClass('form-control');
        });
    </script>
@endsection
