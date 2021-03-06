<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/trix/0.11.2/trix.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script>
        window.App = {!! json_encode([
            'csrfToken' => csrf_token(),
            'user' => \Auth::user(),
            'signedIn' => \Auth::check()
        ]) !!}
    </script>

    <style>
        body {padding-bottom: 100px;}
        .level { display: flex; align-items: center; }
        .flex { flex: 1; }
        [v-cloak] { display: none; }
        .ais-highlight > em {background: yellow;
            font-style: normal;}
    </style>

    @yield('header')

</head>
<body>
<div id="app">
    @include('layouts.nav')

    <main class="py-4">
        @yield('content')
    </main>

    <flash message="{{ session('flash') }}"></flash>
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
