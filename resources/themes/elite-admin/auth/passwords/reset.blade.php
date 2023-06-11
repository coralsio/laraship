@extends('layouts.auth')

@section('title','Reset Password')

@section('content')
    <h4 class="text-center m-b-20">@lang('corals-elite-admin::labels.auth.reset_password')</h4>

    <form method="POST" action="{{ route('password.request') }}">
        {{ csrf_field() }}

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group {{ $errors->has('email') ? ' has-danger' : '' }}">
            <div class="col-xs-12">
                <input type="email" name="email" id="email"
                       class="form-control" placeholder="@lang('User::attributes.user.email')"
                       value="{{ old('email', $email) }}" autofocus/>
                <label>@lang('User::attributes.user.email')</label>
                @if ($errors->has('email'))
                    <small class="form-control-feedback">{{ $errors->first('email') }}</small>
                @endif
            </div>
        </div>
        <div class="form-group {{ $errors->has('password') ? ' has-danger' : '' }}">
            <div class="col-xs-12">
                <input type="password" name="password" id="password"
                       class="form-control" placeholder="@lang('User::attributes.user.password')"
                       value="{{ old('password') }}"/>
                <label>@lang('User::attributes.user.password')</label>

                @if ($errors->has('password'))
                    <small class="form-control-feedback">{{ $errors->first('password') }}</small>
                @endif
            </div>
        </div>
        <div class="form-group {{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
            <div class="col-xs-12">
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="form-control" placeholder="@lang('User::attributes.user.password_confirmation')"
                       value="{{ old('password_confirmation') }}"/>
                <label>@lang('User::attributes.user.password_confirmation')</label>

                @if ($errors->has('password_confirmation'))
                    <small class="form-control-feedback">{{ $errors->first('password_confirmation') }}</small>
                @endif
            </div>
        </div>

        <div class="form-group text-center">
            <div class="col-xs-12 p-b-20">
                <button class="btn btn-block btn-success btn-rounded"
                        type="submit">@lang('corals-elite-admin::labels.auth.reset_password')</button>
            </div>
        </div>
    </form>
@endsection
