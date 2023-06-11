@extends('layouts.auth')

@section('title',trans('corals-elite-admin::labels.auth.login'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h4 class="text-center m-b-20">@lang('corals-elite-admin::labels.auth.sign_in_start_session')</h4>
            @php \Actions::do_action('pre_login_form') @endphp
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{ route('login') }}" id="login-form">
                {{ csrf_field() }}
                <div class="form-group text-center">
                    @if(session('confirmation_user_id'))
                        <a href="{{ route('auth.resend_confirmation') }}">@lang('User::labels.confirmation.resend_email')</a>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('email') ? ' has-danger' : '' }}">
                    <div class="col-xs-12">
                        <input type="email" name="email" id="email"
                               class="form-control" placeholder="@lang('User::attributes.user.email')"
                               value="{{ old('email') }}" autofocus/>
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
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       name="remember"
                                       {{ old('remember') ? 'checked' : '' }} class="custom-control-input"
                                       id="customRemember"/>
                                <label class="custom-control-label"
                                       for="customRemember">@lang('corals-elite-admin::labels.auth.remember_me')</label>
                            </div>
                            <div class="ml-auto">
                                <a href="{{ route('password.request') }}" id="to-recover" class="text-muted"><i
                                            class="fa fa-unlock m-r-5"></i> @lang('corals-elite-admin::labels.auth.forget_password')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group text-center">
                    <div class="col-xs-12 p-b-20">
                        <button class="btn btn-block btn-info btn-rounded"
                                type="submit">@lang('corals-elite-admin::labels.auth.login')</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">
                        <div class="social">
                            @if(config('services.facebook.client_id'))
                                <a class="btn btn-facebook" data-toggle="tooltip"
                                   title="@lang('corals-elite-admin::labels.auth.sign_in_facebook')"
                                   href="{{ route('auth.social', 'facebook') }}">
                                    <i class="fa fa-facebook"></i>
                                </a>
                            @endif
                            @if(config('services.twitter.client_id'))
                                <a class="btn btn-twitter" data-toggle="tooltip"
                                   href="{{ route('auth.social', 'twitter') }}"
                                   title="@lang('corals-elite-admin::labels.auth.sign_in_twitter')">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            @endif
                            @if(config('services.google.client_id'))
                                <a class="btn btn-googleplus" data-toggle="tooltip"
                                   href="{{ route('auth.social', 'google') }}"
                                   title="@lang('corals-elite-admin::labels.auth.sign_in_google')">
                                    <i class="fa fa-google"></i>
                                </a>
                            @endif
                            @if(config('services.github.client_id'))
                                <a class="btn btn-github" data-toggle="tooltip"
                                   href="{{ route('auth.social', 'github') }}"
                                   title="@lang('corals-elite-admin::labels.auth.sign_in_github')">
                                    <i class="fa fa-github"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group m-b-0">
                    <div class="col-sm-12 text-center">
                        <a href="{{ route('register') }}"
                           class="text-info m-l-5"><b>@lang('corals-elite-admin::labels.auth.register_new_account')</b></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection