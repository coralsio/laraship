@extends('layouts.auth')

@section('title','Reset Password')

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form class="form-horizontal" method="POST" id="recoverform" action="{{ route('password.email') }}"
          style="display: block;">
        {{ csrf_field() }}
        <div class="form-group text-center">
            @if(session('confirmation_user_id'))
                <a href="{{ route('auth.resend_confirmation') }}">@lang('User::labels.confirmation.resend_email')</a>
            @endif
        </div>
        
        <div class="form-group ">
            <div class="col-xs-12">
                <h3>@lang('corals-elite-admin::labels.auth.reset_password')</h3>
                <p class="text-muted">@lang('corals-elite-admin::labels.auth.reset_message')</p>
            </div>
        </div>
        <div class="form-group {{ $errors->has('email') ? ' has-danger' : '' }}">
            <div class="col-xs-12">
                <input class="form-control" type="email" name="email" placeholder="@lang('User::attributes.user.email')"
                       value="{{ old('email') }}" autofocus
                />
                <label>@lang('User::attributes.user.email')</label>

                @if ($errors->has('email'))
                    <small class="form-control-feedback">{{ $errors->first('email') }}</small>
                @endif
            </div>
        </div>
        <div class="form-group text-center m-t-20">
            <div class="col-xs-12">
                <button class="btn btn-primary btn-block waves-effect waves-light"
                        type="submit">@lang('corals-elite-admin::labels.auth.send_password_reset')</button>
            </div>
        </div>
    </form>
@endsection
