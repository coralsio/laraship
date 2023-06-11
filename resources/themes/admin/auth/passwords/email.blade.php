@extends('layouts.auth')

@section('title','Reset Password')

@section('content')
    <h4 class="login-box-msg">@lang('corals-admin::labels.auth.reset_password')</h4>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        {{ csrf_field() }}
        <div class="form-group text-center">
            @if(session('confirmation_user_id'))
                <a href="{{ route('auth.resend_confirmation') }}">@lang('User::labels.confirmation.resend_email')</a>
            @endif
        </div>
        <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
            <input type="email" name="email"
                   class="form-control" placeholder="Email"
                   value="{{ old('email') }}" autofocus/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

            @if ($errors->has('email'))
                <div class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </div>
            @endif
        </div>

        <div class="row">
            <!-- /.col -->
            <div class="col-xs-12">
                <button type="submit"
                        class="btn btn-primary btn-block btn-flat">@lang('corals-admin::labels.auth.send_password_reset')</button>
            </div>
            <!-- /.col -->
        </div>
    </form>
@endsection
