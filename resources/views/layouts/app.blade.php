<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script>
      window.App = {!! json_encode([
               'user' => Auth::user(),
               'signedIn' => Auth::check()
           ]) !!};
    </script>

    <style>
    [v-cloak] {
        display: none
    }

    body {
        padding-bottom: 100px;
    }

    .page-header {
        padding-bottom: 9px;
        margin: 40px 0 20px;
        border-bottom: 1px solid #eee;
    }
    </style>

    @yield('head')
</head>

<body>

    <div id="app" v-cloak>

        @include('layouts.nav')

        <main class="py-4 mx-5 px-5">
            @yield('content')


        </main>
        <flash message="{{ session('flash') }}"> </flash>



    </div>
</body>

</html>
