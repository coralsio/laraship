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
<body>

<!-- Site wrapper -->
<div id="page-container"
     class="sidebar-o {{\Theme::current()->name=='corals-one-ui-dark'||\Theme::current()->name=='corals-one-ui'?'sidebar-dark':''}}  enable-page-overlay side-scroll page-header-fixed main-content-narrow">


@include('partials.header')

<!-- =============================================== -->
@if(!(isset($hide_sidebar) && $hide_sidebar))
    <!-- Left side column. contains the sidebar -->
    @include('partials.sidebar')
@endif
<!-- =============================================== -->
    <main id="main-container">
        @yield('content_header')
        <section class="content">
            <div class="row mb-2">
                <div class="col-md-12 text-right">
                    @yield('custom-actions')
                    @yield('actions')
                </div>
            </div>
            @yield('content')
        </section>
    </main>

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
