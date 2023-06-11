@extends('layouts.auth')

@section('title',trans('corals-one-ui::labels.auth.login'))


@section('content')

    <div class="row justify-content-center">

        <div class="col-md-8 col-lg-6 col-xl-4">
            <div class="block block-rounded block-themed mb-0">
                @php \Actions::do_action('pre_login_form') @endphp

                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Log In</h3>
                    <div class="block-options">
                        <a class="btn-block-option font-size-sm" href="{{url('password/reset')}}">Forgot Password?</a>
                        {{--                        <a class="btn-block-option" href="op_auth_signup.html" data-toggle="tooltip"--}}
                        {{--                           data-placement="left" title="New Account">--}}
                        {{--                            <i class="fa fa-user-plus"></i>--}}
                        {{--                        </a>--}}
                    </div>
                </div>

                <div class="block-content">
                    <div class="p-sm-3 px-lg-4 py-lg-5">
                        <h1 class="h2 mb-1">{{ \Settings::get('site_name', 'Corals') }}</h1>
                        <p class="text-muted">
                            Welcome, please login.
                        </p>

                        <form method="POST" action="{{ route('login') }}" id="login-form">

                            <div class="py-3">

                                {{ csrf_field() }}
                                <div class="form-group text-center">
                                    @if(session('confirmation_user_id'))
                                        <a href="{{ route('auth.resend_confirmation') }}">@lang('User::labels.confirmation.resend_email')</a>
                                    @endif
                                </div>
                                <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <input type="email" name="email" id="email"
                                           class="form-control form-control-alt form-control-lg"
                                           placeholder="@lang('User::attributes.user.email')"
                                           value="{{ old('email') }}" autofocus/>
                                    <span class="glyphicon glyphicon-envelope form-control-icon"></span>

                                    @if ($errors->has('email'))
                                        <div class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                                    <input type="password" name="password"
                                           class="form-control form-control-alt form-control-lg"
                                           placeholder="@lang('User::attributes.user.password')" id="password"/>
                                    <span class="glyphicon glyphicon-lock form-control-icon"></span>

                                    @if ($errors->has('password'))
                                        <div class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input"
                                               id="login-remember"
                                               name="remember" {{ old('remember') ? 'checked' : '' }} />
                                        <label class="custom-control-label font-w400"
                                               for="login-remember">@lang('corals-one-ui::labels.auth.remember_me')</label>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 col-xl-5">
                                    <button type="submit" class="btn btn-block btn-alt-primary">
                                        <i class="fa fa-fw fa-sign-in mr-1"></i>
                                        @lang('corals-one-ui::labels.auth.login')
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

@section('js')
    <script>
        $(document).ready(function () {
            if (!$(".socials-buttons").children().length > 0) {
                $(".or-separator").remove();
            }
        });
    </script>
@endsection
