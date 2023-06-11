@extends('layouts.auth')

@section('title',trans('corals-admin::labels.auth.login'))

@section('css')
    <style type="text/css">
        .login-box, .register-box {
            width: 720px;
            margin: 6% auto;
        }

        .login-left {
            border-right: 4px solid #ddd;
        }

        @media (max-width: 470px) {
            .login-box, .register-box {
                width: 340px;
            }

            .login-left {
                border-right: none;
            }
        }

        .or-separator {
            text-align: center;
            margin: 10px 0;
            text-transform: uppercase;
        }

        .or-separator:after, .or-separator:before {
            content: ' -- ';
        }
    </style>
@endsection

@section('content')
    <h4 class="login-box-msg">@lang('corals-admin::labels.auth.sign_in_start_session')</h4>
    @php \Actions::do_action('pre_login_form') @endphp
    <div class="row">
        <div class="col-md-6 login-left">
            <form method="POST" action="{{ route('login') }}" id="login-form">
                {{ csrf_field() }}
                <div class="form-group text-center">
                    @if(session('confirmation_user_id'))
                        <a href="{{ route('auth.resend_confirmation') }}">@lang('User::labels.confirmation.resend_email')</a>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" name="email" id="email"
                           class="form-control" placeholder="@lang('User::attributes.user.email')"
                           value="{{ old('email') }}" autofocus/>
                    <span class="glyphicon glyphicon-envelope form-control-icon"></span>

                    @if ($errors->has('email'))
                        <div class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </div>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" name="password" class="form-control"
                           placeholder="@lang('User::attributes.user.password')" id="password"/>
                    <span class="glyphicon glyphicon-lock form-control-icon"></span>

                    @if ($errors->has('password'))
                        <div class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn bg-purple btn-block">
                            <span class="fa fa-sign-in"></span>
                            @lang('corals-admin::labels.auth.login')
                        </button>
                    </div>
                </div>
                <div class="row">

                    <div class="col-xs-12">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox"
                                       name="remember" {{ old('remember') ? 'checked' : '' }} /> @lang('corals-admin::labels.auth.remember_me')
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6 login-right">
            @if(\Settings::get('registration_enabled', true))
            <a href="{{ route('register') }}"
               class="btn bg-olive btn-social btn-block">
                <span class="fa fa-user-o"></span>
                @lang('corals-admin::labels.auth.register_new_account')
            </a>
            @endif
            <a href="{{ route('password.request') }}"
               class="btn bg-yellow btn-social btn-block">
                <span class="fa fa-question"></span>
                @lang('corals-admin::labels.auth.forget_password')
            </a>
            <div class="or-separator">@lang('Corals::labels.or')</div>
            <div class="socials-buttons">
                @if(config('services.facebook.client_id'))
                    <a class="btn btn-block btn-social btn-facebook" href="{{ route('auth.social', 'facebook') }}">
                        <span class="fa fa-facebook"></span> @lang('corals-admin::labels.auth.sign_in_facebook')
                    </a>
                @endif
                @if(config('services.twitter.client_id'))
                    <a class="btn btn-block btn-social btn-twitter" href="{{ route('auth.social', 'twitter') }}">
                        <span class="fa fa-twitter"></span> @lang('corals-admin::labels.auth.sign_in_twitter')
                    </a>
                @endif
                @if(config('services.google.client_id'))
                    <a class="btn btn-block btn-social btn-google" href="{{ route('auth.social', 'google') }}">
                        <span class="fa fa-google"></span> @lang('corals-admin::labels.auth.sign_in_google')
                    </a>
                @endif
                @if(config('services.github.client_id'))
                    <a class="btn btn-block btn-social btn-github" href="{{ route('auth.social', 'github') }}">
                        <span class="fa fa-github"></span> @lang('corals-admin::labels.auth.sign_in_github')
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            if (!$(".socials-buttons").children().length > 0) {
                $(".or-separator").remove();
            }
        });
    </script>
@endsection