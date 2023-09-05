<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title') | APOV</title>

        {!! Meta::tags() !!}
        {!! Meta::tag('csrf-token', csrf_token()) !!}

        <link href="{{ asset("img/logomarca/logo_flavicon.png") }}" rel="shortcut icon" type="image/x-icon">

        <link href="{{ asset("css/modern.css")}}" rel="stylesheet">
        <link href="{{ asset("css/fontawesome/all.css")}}" rel="stylesheet">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }

            h1.header-title {
                font-size: 1.2em;
            }

            .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__clear {
                margin-top: 0
            }

            .text-right{
                text-align: right !important;
            }

            @media only screen and (max-width: 500px) {
                .logoProfile {
                    width: 130px;
                }

                .displayHeader {
                    display: inline;
                }

                .displayPhotoProfile {
                    width: 100%;
                }
            }

            @media only screen and (min-width: 501px) {
                .logoProfile {
                    width: 170px;
                }

                .displayHeader {
                    display: flex;
                }

                .displayPhotoProfile {
                    width: 307px;
                }
            }
        </style>
        @stack('stylesheet')

        @routes
    </head>
    <body class="antialiased">
        <div class="wrapper">
            <div class="main">
                <nav class="navbar navbar-expand navbar-theme">
                    <div class="sidebar-toggle d-flex me-2">
                        <nav id="sidebar" class="sidebar text-center">
                            @if(empty($currentUser->instituicao->logomarca_secundaria))
                                <a class="text-center" href="{{ env('APP_URL') }}">
                                    <img src="{{ asset($currentUser->instituicao->logomarca) }}" class="logoProfile" style="margin: 10px 20px 10px 20px; max-width: 140px; max-height: 120px;" />
                                </a>
                            @else
                                <div class="row">
                                    <div class="col-md-5">
                                        <a class="text-center" href="{{ env('APP_URL') }}">
                                            <img src="{{ asset($currentUser->instituicao->logomarca) }}" class="logoProfile" style="margin: 15px 10px 15px 10px; max-width: 120px; max-height: 100px;" />
                                        </a>
                                    </div>
                                    <div class="col-md-5" style="margin-left: 30px;">
                                        <a class="text-center" href="{{ env('APP_URL') }}">
                                            <img src="{{ asset($currentUser->instituicao->logomarca_secundaria) }}" class="logoProfile" style="margin: 15px 10px 15px 10px; max-width: 120px; max-height: 100px;" />
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </nav>
                    </div>

                    <div class="navbar-collapse collapse">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown ms-lg-2">
                                <a class="nav-link dropdown-toggle position-relative" href="#" id="userDropdown" data-bs-toggle="dropdown">
                                    <i class="align-middle fas fa-cog"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="{{ env('APP_URL') }}/meus-dados/"><i class="align-middle me-1 fas fa-fw fa-user"></i> Meus Dados</a>
                                    <a class="dropdown-item" href="{{ env('APP_URL') }}/mudar-senha/"><i class="align-middle me-1 fas fa-fw fa-key"></i> Trocar Senha</a>
                                    <div class="dropdown-divider"></div>
                                    <form method="POST" action="{{ route('logout')  }}">
                                        {!! csrf_field() !!}
                                        <button type="submit" class="dropdown-item"><i class="align-middle me-1 fas fa-fw fa-arrow-alt-circle-right"></i> Sair do Sistema</button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <main class="content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </main>
                @include('layout.components.footer')
            </div>
        </div>


        <div id="" class="">
            <div class="splash active">
                <div class="splash-icon"></div>
            </div>

            <div style="background: #153d77;position: fixed;z-index: 1000;width: 100%;" class="displayHeader">
                <div style="text-align: center;" class="displayPhotoProfile">

                </div>
                <div style="width: 100%;">
                    <nav class="navbar navbar-expand navbar-theme" style="margin-top: 5px;">
                        <a class="sidebar-toggle d-flex me-2" onclick="estadoMenu();">
                            <i class="hamburger align-self-center"></i>
                        </a>

                        <div class="navbar-collapse collapse">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item dropdown ms-lg-2">

                                </li>
                            </ul>
                        </div>

                    </nav>
                </div>
            </div>
        </div>

        <script src="{{ asset('js/pace.min.js') }}"></script>
        <script src="{{ asset("js/app.js")}}"></script>
        <script>
            window.Laravel = JSON.stringify(<?php echo json_encode([
                'can-update' => !Auth::user()->consulta,
                'can-only-select' => Auth::user()->consulta ? true : false,
            ], true) ?>)
        </script>
        @stack('scripts')

    </body>
</html>
