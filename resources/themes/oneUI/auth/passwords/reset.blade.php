@extends('layouts.auth')

@section('title','Reset Password')

@section('content')

    <div class="row justify-content-center">

        <div class="col-md-8 col-lg-6 col-xl-4">
            <div class="block block-rounded block-themed mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">@lang('corals-one-ui::labels.auth.reset_password')</h3>
                    <div class="block-options">
                        <a class="btn-block-option js-tooltip-enabled" href="{{url('login')}}"
                           data-toggle="tooltip" data-placement="left" title="" data-original-title="Sign In">
                            <i class="fa fa-sign-in"></i>
                        </a>
                    </div>
                </div>

                <div class="block-content">
                    <div class="p-sm-3 px-lg-4 py-lg-5">
                        <form method="POST" action="{{ route('password.request') }}">
                            {{ csrf_field() }}

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                <input type="email" name="email"
                                       class="form-control form-control-alt form-control-lg"
                                       placeholder="@lang('User::attributes.user.email')"
                                       value="{{ old('email') }}"/>
                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                                @if ($errors->has('email'))
                                    <div class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                                <input type="password" name="password"
                                       class="form-control form-control-alt form-control-lg"
                                       placeholder="@lang('User::attributes.user.password')"/>
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                @if ($errors->has('password'))
                                    <div class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <input type="password" name="password_confirmation"
                                       class="form-control form-control-alt form-control-lg"
                                       placeholder="@lang('User::attributes.user.retype_password')"/>
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                                @if ($errors->has('password_confirmation'))
                                    <div class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 col-xl-6">
                                    <button type="submit" class="btn btn-block btn-alt-primary">
                                        <i class="fa fa-fw fa-user-lock mr-1"></i> @lang('corals-one-ui::labels.auth.reset_password')
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
