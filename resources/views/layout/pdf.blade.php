<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title') | APOV</title>

        {!! Meta::tags() !!}

        <link href="{{ public_path("css/modern.css") }}" rel="stylesheet">
        <link href="{{ public_path("css/fontawesome/all.css")}}" rel="stylesheet">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <main class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </main>

        <script src="{{ public_path('js/pace.min.js') }}"></script>
    </body>
</html>
