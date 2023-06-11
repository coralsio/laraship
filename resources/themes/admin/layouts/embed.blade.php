<html>
<head>
    {!! \Assets::css() !!}


    <link rel="stylesheet" href="{{ asset(\Theme::url('plugins/font-awesome/css/font-awesome.min.css'))}}"/>
    <link rel="stylesheet" href="{{ asset(\Theme::url('plugins/bootstrap/dist/css/bootstrap.min.css')) }}"/>
    @yield('css')
    @stack('partial_css')

</head>

<body>

@include($view, $view_variables)


<script src="{{ asset(\Theme::url('plugins/jquery/dist/jquery.min.js')) }}" type="text/javascript"></script>
<script src="{{asset(\Theme::url('plugins/bootstrap/dist/js/bootstrap.min.js')) }}" type="text/javascript"></script>

{!! Assets::js() !!}

@yield('js')

</body>
</html>
