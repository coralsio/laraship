<!DOCTYPE html>
<html lang="{{ \Language::getCode() }}" dir="{{ \Language::getDirection() }}">
<head>
    <style>
        .end-impersonation-btn {
            position: fixed;
            bottom: 10px;
            right: 10px;
            z-index: 1000;
            cursor: pointer;
        }

    </style>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        @if($unreadNotifications = user()->unreadNotifications()->count())
            ({{ $unreadNotifications }})
        @endif
        @yield('title') | {{ \Settings::get('site_name', 'Corals') }}
    </title>

    <link rel="icon" type="image/png" sizes="16x16" href="{{ \Settings::get('site_favicon') }}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('partials.scripts.header')

    <style type="text/css">
        {!! \Settings::get('custom_admin_css', '') !!}
    </style>
</head>
<body class="horizontal-nav skin-megna fixed-layout">

<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
    <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label">{{ \Settings::get('site_name', 'Corals') }}</p>
    </div>
</div>

<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper">

    @can('leaveImpersonation' , [\Corals\User\Models\User::class,session()->get('impersonator')])
        <div class="end-impersonation-btn">
            <a
                    class="btn btn-sm btn-warning text-dark"
                    href="{{ route('impersonation.leave') }}"
                    data-action="post"
                    data-page_action="redirectTo"
            >
                <i class="fa fa-fw fa-power-off text-dark"></i>
                End Impersonation
            </a>
        </div>
    @endcan

    @include('partials.header')

    @include('partials.sidebar')

    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <div class="d-flex justify-content-between">
                <div>
                    @yield('content_header')
                </div>
                <div class="align-self-center text-right m-b-10">
                    @yield('custom-actions')
                    @yield('actions')
                </div>
            </div>

            @yield('content')
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
    </div>

    @include('partials.footer')

    @include('components.modal',['id'=>'global-modal'])

    @include('partials.scripts.footer')
</div>

<script type="text/javascript">
    {!! \Settings::get('custom_admin_js', '') !!}
</script>
</body>
</html>