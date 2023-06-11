@extends('layouts.auth')

@section('title',trans('corals-elite-admin::labels.auth.register'))
@section('content')
    <h4 class="text-center m-b-20">
        @lang('corals-elite-admin::labels.auth.register_new_account')
    </h4>

    <form method="POST" action="{{ route('register') }}" class="form-horizontal">
        {{ csrf_field() }}
        <div class="form-group {{ $errors->has('name') ? ' has-danger' : '' }}">
            <input type="text" name="name"
                   class="form-control" placeholder="@lang('User::attributes.user.name')"
                   value="{{ old('name') }}" autofocus/>
            <label>@lang('User::attributes.user.name')</label>

            @if ($errors->has('name'))
                <small class="form-control-feedback">{{ $errors->first('name') }}</small>
            @endif
        </div>
        <div class="form-group {{ $errors->has('last_name') ? ' has-danger' : '' }}">
            <input type="text" name="last_name"
                   class="form-control" placeholder="@lang('User::attributes.user.last_name')"
                   value="{{ old('last_name') }}" autofocus/>
            <label>@lang('User::attributes.user.last_name')</label>

            @if ($errors->has('last_name'))
                <small class="form-control-feedback">{{ $errors->first('last_name') }}</small>
            @endif
        </div>
        <div class="form-group {{ $errors->has('email') ? ' has-danger' : '' }}">
            <input type="email" name="email"
                   class="form-control" placeholder="@lang('User::attributes.user.email')"
                   value="{{ old('email') }}"/>
            <label>@lang('User::attributes.user.email')</label>

            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            @if ($errors->has('email'))
                <small class="form-control-feedback">{{ $errors->first('email') }}</small>
            @endif
        </div>
        <div class="form-group {{ $errors->has('password') ? ' has-danger' : '' }}">
            <input type="password" name="password" class="form-control"
                   placeholder="@lang('User::attributes.user.password')"/>
            <label>@lang('User::attributes.user.password')</label>
            @if ($errors->has('password'))
                <small class="form-control-feedback">{{ $errors->first('password') }}</small>
            @endif
        </div>
        <div class="form-group {{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
            <input type="password" name="password_confirmation" class="form-control"
                   placeholder="@lang('User::attributes.user.retype_password')"/>
            <label>@lang('User::attributes.user.retype_password')</label>

            @if ($errors->has('password_confirmation'))
                <small class="form-control-feedback">{{ $errors->first('password_confirmation') }}</small>
            @endif
        </div>
        @if( $is_two_factor_auth_enabled = \TwoFactorAuth::isActive())
            @if( $twoFaView = \TwoFactorAuth::TwoFARegistrationView())
                <div id="2fa-registration-details">
                    @include($twoFaView)
                </div>
            @endif
        @endif
        <div class="custom-control custom-checkbox">
            <input type="checkbox" value="1"
                   name="terms"
                   {{ old('terms') ? 'checked' : '' }} class="custom-control-input"
                   id="termsCheckbox"/>
            <label class="custom-control-label"
                   for="termsCheckbox">
                <strong>@lang('corals-elite-admin::labels.auth.agree')
                    <a href="#" data-toggle="modal" id="terms-anchor"
                       data-target="#terms">@lang('corals-elite-admin::labels.auth.terms')</a>
                </strong>
            </label>
        </div>

        @if($errors->has('terms'))
            <div class="has-danger">
                <small class="form-control-feedback">@lang('corals-elite-admin::labels.auth.accept_terms')</small>
            </div>
        @endif

        <div class="form-group text-center mt-2">
            <div class="col-xs-12">
                <button class="btn btn-block btn-success btn-rounded"
                        type="submit">@lang('corals-elite-admin::labels.auth.register')
                </button>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col text-center">
            <a href="{{ route('login') }}">@lang('corals-elite-admin::labels.auth.already_have_account')</a><br>
        </div>
    </div>
@endsection


