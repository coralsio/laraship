@extends('layouts.auth')

@section('title','Reset Password')

@section('content')

    <div class="row justify-content-center">

        <div class="col-md-8 col-lg-6 col-xl-4">
            <div class="block block-rounded block-themed mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">RESET PASSWORD</h3>
                    <div class="block-options">
                        <a class="btn-block-option js-tooltip-enabled" href="{{url('login')}}"
                           data-toggle="tooltip" data-placement="left" title="" data-original-title="Sign In">
                            <i class="fa fa-sign-in"></i>
                        </a>
                    </div>
                </div>

                <div class="block-content">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="p-sm-3 px-lg-4 py-lg-5">

                        <h1 class="h2 mb-1">{{ \Settings::get('site_name', 'Corals') }}</h1>
                        <p class="text-muted">
                            Please provide your account’s email and we will send you your password.
                        </p>


                        <form method="POST" action="{{ route('password.email') }}">
                            {{ csrf_field() }}
                            <div class="form-group text-center">
                                @if(session('confirmation_user_id'))
                                    <a href="{{ route('auth.resend_confirmation') }}">@lang('User::labels.confirmation.resend_email')</a>
                                @endif
                            </div>
                            <div class="form-group py-3 has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                <input type="email" name="email"
                                       class="form-control form-control-alt form-control-lg" placeholder="Email"
                                       value="{{ old('email') }}" autofocus/>
                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                                @if ($errors->has('email'))
                                    <div class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 col-xl-5">
                                    <button type="submit" class="btn btn-block btn-alt-primary">
                                        <i class="fa fa-fw fa-envelope mr-1"></i> Send Mail
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
