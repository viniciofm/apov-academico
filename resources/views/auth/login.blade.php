<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Modern, flexible and responsive Bootstrap 5 admin &amp; dashboard template">
    <meta name="author" content="Bootlab">

    <title>{{__('login')}} | APOV</title>

    <style>
        body {
            opacity: 0;
        }
    </style>

    <link href="{{ asset("css/login/main.css")}}" rel="stylesheet">
    <link href="{{ asset("css/modern.css")}}" rel="stylesheet">
    <link href="{{ asset("css/fontawesome/all.css")}}" rel="stylesheet">

<body class="theme-blue" style="background-color: #06309d;">
<div class="splash">
    <div class="splash-icon"></div>
</div>

<main class="main h-100 w-100">
    <div class="container h-100">
        <div class="row h-100">
            <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">
                    <div class="container-login100">
                        <div class="wrap-login100">
                            @if($errors->any())
                                <div class="alert alert-primary alert-outline alert-dismissible" role="alert" style="width:100%;padding: 1px">
                                    <div class="alert-icon">
                                        <i class="far fa-fw fa-bell"></i>
                                    </div>
                                    <div class="alert-message">
                                        <!-- Validation Errors -->
                                        <x-auth-validation-errors class="mb-4" :errors="$errors" />
                                    </div>

                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            <form class="login100-form validate-form" method="POST">
                                @csrf

                                <span class="login100-form-title mb-4">
                                    <div class="text-center mt-4">
                                        <h1 class="h2">Bem vindo!</h1>
                                        <p class="lead">
                                            {{ __('Sign in to your account to continue') }}
                                        </p>
                                    </div>
                                </span>

                                <div class="wrap-input100 validate-input mb-3" data-validate="Insira um e-mail válido">
                                    <input class="input100" type="text" id="email" name="email" :value="old('email')" placeholder="Usuário" required autofocus>
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100" style="text-align: left;">
							            <i class="fa-solid fa-user"></i>
						            </span>
                                </div>

                                <div class="wrap-input100 validate-input mb-3" data-validate="Informe sua senha">
                                    <input class="input100" type="password" id="password" name="password" placeholder="Senha">
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
                                        <i class="fa-solid fa-lock"></i>
                                    </span>
                                </div>

                                <div class="wrap-input100 validate-input mb-1" data-validate="Informe sua senha">
                                    <span class="focus-input100"></span>
                                    <select class="input100" style="border-color: white;" name="tipo_usuario_id" id="tipo_usuario_id" required="required">
                                        <option value="" disabled selected>Não selecionado</option>
                                        @foreach($tipos as $tipo)
                                            <option value="{{$tipo->id}}">{{ __($tipo->nome) }}</option>
                                        @endforeach
                                    </select>
                                    <span class="symbol-input100">
                                        <i class="fa-solid fa-users-line"></i>
                                    </span>
                                </div>

                                <div class="container-login100-form-btn" data-tilt="">
                                    <button class="login100-form-btn" type="submit">
                                        Entrar
                                    </button>
                                </div>

                                <div class="text-center p-t-12 mb-3">
                                    <span class="txt1">
                                        Esqueceu seu
                                    </span>
                                    <a class="txt2" href="esqueci-minha-senha">
                                        Usuário ou Senha?
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="js/app.js"></script>

<svg id="SvgjsSvg1001" width="2" height="0" xmlns="http://www.w3.org/2000/svg" version="1.1"
     xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev"
     style="overflow: hidden; top: -100%; left: -100%; position: absolute; opacity: 0;">
    <defs id="SvgjsDefs1002"></defs>
    <polyline id="SvgjsPolyline1003" points="0,0"></polyline>
    <path id="SvgjsPath1004" d="M0 0 "></path>
</svg>
</body>
</html>
