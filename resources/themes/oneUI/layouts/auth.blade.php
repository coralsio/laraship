<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') | {{ \Settings::get('site_name', 'Corals') }}</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Fira+Sans">
    @include('partials.scripts.header')



    @yield('css')

</head>
<body>

<!-- Main content -->
<div class="page-container">
    <!-- /.login-logo -->
    <div class="main-container">
        <div class="hero-static">
            <div class="content">

                @yield('content')
                <div class="row">
                    <div class="col-md-12 text-center m-t-10">
                        @if(count(\Settings::get('supported_languages', [])) > 1)
                            {!! \Language::flags('list-inline') !!}
                        @endif
                    </div>
                </div>
            </div>

        </div>
        @include('partials.footer')

    </div>

    <!-- /.login-box-body -->
</div>
<!-- /.content -->

@include('partials.scripts.footer')
@include('components.modal',['id'=>'global-modal'])

@php \Actions::do_action('admin_footer_js') @endphp


@yield('js')

</body>
</html>
