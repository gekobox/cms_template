<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <title>My Vendata</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Prevent caching -->
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />

    <link href="/vendor/material-icons/material-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/vendor/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="/vendor/materialize/css/materialize.min.css">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    @include('../elements/loader')
    <div id="app" class="blue-grey-text text-darken-2">
        @yield('content')
    </div>

    <script src="/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="/vendor/jquery-ui/jquery-ui.min.js"></script>
    <script src="/vendor/jquery-ui/jquery.ui.touch-punch.min.js"></script>
    <script src="/vendor/materialize/js/materialize.min.js"></script>
    <script src="/vendor/vue/vue.js"></script>{{-- Vue.js --}}
    <script src="/vendor/vue-router/vue-router.js"></script>{{-- Vue.js router --}}
    <script src="/vendor/tinymce/js/tinymce/tinymce.min.js"></script>
    <script src="/js/config.js"></script>
    <script src="/js/app.js"></script>
    @yield('js')
</body>
</html>
