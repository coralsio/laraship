<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') | {{ \Settings::get('site_name', 'Corals') }}</title>

    <link rel="icon" type="image/png" sizes="16x16" href="{{ \Settings::get('site_favicon') }}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! \Theme::css('css/login-register-lock.css') !!}

    @include('partials.scripts.header')

    <style type="text/css">
        @if($background = \Settings::get('login_background'))
            {!! 'body {'.$background.'}' !!}
        @endif
    </style>

    @yield('css')
    @stack('partial_css')

</head>
<body class="skin-default card-no-border">

<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
    <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label">{{ \Settings::get('site_name', 'Corals') }}</p>
    </div>
</div>

<section id="wrapper" class="login-register login-sidebar">
    <div class="login-box card">
        <div class="card-body">
            <div class="text-center">
                <a href="{{ url('/') }}">
                    <img src="{{ \Settings::get('site_logo') }}"
                         alt="{{ \Settings::get('site_name') }}"
                         class="img-fluid" style="max-width: 200px;"/>
                </a>
                <hr/>
            </div>
            @yield('content')
            <div class="row">
                <div class="col-md-12 text-center">
                    @if(count(\Settings::get('supported_languages', [])) > 1)
                        {!! \Language::flags('list-inline') !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<style type="text/css">
    @if($background = \Settings::get('login_background'))
        {!! '.login-register {'.$background.'}' !!}
    @endif
</style>

@include('partials.scripts.footer')

@yield('js')

@component('components.modal',[
        'id'=>'terms',
        'header'=>trans('corals-elite-admin::labels.auth.terms_modal_header',['siteName'=>\Settings::get('site_name')])
    ])
    {!! \Settings::get('terms_and_policy') !!}
@endcomponent

</body>
</html>
