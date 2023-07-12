<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Jovem Aprendiz - Sistema Acadêmico">
    <meta name="author" content="viniciofm">

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

                                <div class="mb-4 text-sm text-gray-600">
                                    {{ __('Esqueceu sua senha? Sem problemas. Nos informe o seu endereço de e-mail e nós lhe enviaremos um link para a redefinição.') }}
                                </div>

                                <!-- Session Status -->
                                <x-auth-session-status class="mb-4" :status="session('status')" />

                                <!-- Validation Errors -->
                                <x-auth-validation-errors class="mb-4" :errors="$errors" />

                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf

                                    <!-- Email Address -->
                                    <div>
                                        <label style="margin-left: 1px;">E-mail</label>
                                        <div class="wrap-input100 validate-input mb-3" data-validate="Insira um e-mail válido">
                                            <input class="input100" type="text" id="email" name="email" :value="__('email')" required autofocus>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-end mt-4">
                                        <button class="login100-form-btn bg-danger" type="submit">
                                            Solicitar Redefinição de Senha
                                        </button>
                                        <a href="/" class="login100-form-btn bg-primary mt-2">
                                            Voltar
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
