<!DOCTYPE html>
<html lang="{{ \Language::getCode() }}" dir="{{ \Language::getDirection() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        @if($unreadNotifications = user()->unreadNotifications()->count())
            ({{ $unreadNotifications }})
        @endif
        @yield('title') | {{ \Settings::get('site_name', 'Corals') }}
    </title>

    <link rel="shortcut icon" href="{{ \Settings::get('site_favicon') }}" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Fira+Sans">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('partials.scripts.header')

    <style type="text/css">
        {!! \Settings::get('custom_admin_css', '') !!}
    </style>
</head>
<body class="skin-purple-light {{ isset($hide_sidebar) && $hide_sidebar?'sidebar-hidden':'sidebar-mini'}}">
<!-- Site wrapper -->
<div class="wrapper">


@include('partials.header')

<!-- =============================================== -->
@if(!(isset($hide_sidebar) && $hide_sidebar))
    <!-- Left side column. contains the sidebar -->
    @include('partials.sidebar')
@endif
<!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
    @yield('content_header')
    <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    @yield('custom-actions')
                </div>
                <div class="col-md-6 text-right" style="padding-bottom: 10px;">
                    @yield('actions')
                </div>
            </div>
            @yield('content')
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('partials.footer')

    @include('components.modal',['id'=>'global-modal'])

    @include('partials.scripts.footer')

    <script type="text/javascript">
        {!! \Settings::get('custom_admin_js', '') !!}
    </script>
</div>
</body>
</html>
