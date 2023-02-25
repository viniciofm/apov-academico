<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title') | APOV</title>

        {!! Meta::tags() !!}

        <link href="http://academico/css/modern.css" rel="stylesheet">
        <link href="http://academico/css/fontawesome/all.css" rel="stylesheet">

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

        <style>
            body{
                background-color: white !important;
            }
            .grid-pdf > .col-md-2{
                display:inline-block;
            }
        </style>

        <script src="http://academico/js/pace.min.js"></script>
    </body>
</html>
