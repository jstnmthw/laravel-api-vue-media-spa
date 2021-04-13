<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <meta name="RATING" content="RTA-5042-1996-1400-1577-RTA" /> --}}

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title-->
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Preload -->
    <link rel="dns" href="//fonts.gstatic.com">
    <link rel="dns" href="{{config('const.media_cdn_url')}}">

    <!-- Favico -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <vue-progress-bar></vue-progress-bar>
        <navbar></navbar>
        <router-view></router-view>
        <main-footer></main-footer>
    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>
