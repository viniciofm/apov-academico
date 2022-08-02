<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title') | APOV</title>

        <link href="{{ asset("css/modern.css")}}" rel="stylesheet">
        <link href="{{ asset("css/fontawesome/all.css")}}" rel="stylesheet">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
        @stack('stylesheet')
    </head>
    <body class="antialiased">
        <div class="row">
            <div class="">
                @yield('content')
            </div>

            @include('layout.components.footer')
        </div>
        <script src="{{ asset("js/app.js")}}"></script>
        @stack('scripts')
    </body>
</html>
