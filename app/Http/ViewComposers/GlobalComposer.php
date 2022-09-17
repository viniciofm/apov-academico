<?php

namespace App\Http\ViewComposers;

use App\Entities\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GlobalComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $usuario = Auth::user();

        if ($usuario) {
            $view->with('currentUser', $usuario);

            $saudacaoUser = Auth::user()->genero->nome == 'Feminino' ? 'Bem-vinda' : 'Bem-vindo';
            $view->with('saudacaoUser', $saudacaoUser);

            $menu = Menu::where('tipo_usuario_id', $usuario->tipo_usuario_id)->orderBy('posicao')->get();
            $view->with('menu', $menu);
        }
    }
}
