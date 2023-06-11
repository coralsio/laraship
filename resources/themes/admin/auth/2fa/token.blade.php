@extends('layouts.auth')

@section('title','Login')

@section('content')
    <div class="register-logo">
    </div>

    <div class="register-box-body">

        <div @include(\TwoFactorAuth::getTokenPageView())
    </div>
    <p>@lang('corals-admin::labels.auth.verify_login_here')</p>
    <form method="POST" action="{{url('auth/token')}}">
        {!! csrf_field() !!}

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group has-feedback">
            <input type="text" name="token" class="form-control" placeholder="@lang('User::attributes.user.token')">
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <button type="submit"
                        class="btn btn-success btn-block">@lang('corals-admin::labels.auth.verify_token')</button>
                <br/>
                @if(\TwoFactorAuth::canSendToken($user))
                    <a class="" href="{{ url('auth/token') }}">@lang('corals-admin::labels.auth.send_token')</a><br>
                @endif
            </div><!-- /.col -->
        </div>
    </form>
@endsection

@section('js')
@endsection