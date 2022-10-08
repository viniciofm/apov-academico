<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\User\Http\Services\TipoUsuarioService;

class AuthenticatedSessionController extends Controller
{
    /**
     * @var TipoUsuarioService $tipoUsuarioService
     */
    protected $tipoUsuarioService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TipoUsuarioService $tipoUsuarioService)
    {
        $this->tipoUsuarioService = $tipoUsuarioService;
    }

    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login')->with(['tipos' => $this->tipoUsuarioService->all(false, 'nome')]);
    }

    /**
     * @param  LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
