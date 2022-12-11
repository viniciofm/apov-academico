<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\User;
use Modules\User\Http\Requests\PasswordRequestValidator;
use Modules\User\Http\Requests\UserRequestValidator;
use Modules\User\Http\Services\UserService;

class UserController extends Controller
{
    /**
     * @var UserService $service
     */
    protected $service;

    /**
     * @param  UserService  $service
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('modules.usuario.index');
    }

    /**
     * @param  UserRequestValidator  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(UserRequestValidator $request)
    {
        $r = $request->all();
        //validar cadastro
        $canRegister = $this->service->canRegisterCadastro($r, 'admin');
        if (!$canRegister){
            throw new \Exception('Verfique os dados informados: Os dados já encontram-se registrados na instituição!');
        }

        try {
            $data = $this->service->createUserAdmin($request);

            if (!$data) {
                throw new \Exception('Não foi possível registrar o novo item!');
            }
        } catch (\Exception $e) {
            return \response()->json(['message' => $e->getMessage()], 500);
        }
        return \response()->json([
            'success' => true,
            'message' => 'Usuário cadastrado!'
        ], 201);
    }

    /**
     * @param  UserRequestValidator  $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(UserRequestValidator $request, $id)
    {
        $r = $request->all();
        //validar atualização
        $registro = $this->service->find($id);
        $canRegister = $this->service->canRegisterCadastro($r, $registro->tipo_usuario->nome, $registro->id);
        if (!$canRegister){
            throw new \Exception('Verfique os dados informados: Os dados já encontram-se registrados na instituição!');
        }

        try {
            $data = $this->service->updateUserAdmin($id, $request);

            if (!$data) {
                throw new \Exception('Não foi possível atualizar o item!');
            }
        } catch (\Exception $e) {
            return \response()->json(['message' => $e->getMessage()], 500);
        }
        return \response()->json([
            'success' => true,
            'message' => 'Usuário atualizado!'
        ], 201);
    }

    /**
     * @param  UserRequestValidator  $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function updatePassword(PasswordRequestValidator $request)
    {
        try {
            $data = $this->service->updatePassword($request->all());

            if (!$data) {
                throw new \Exception('Não foi possível atualizar o item!');
            }
        } catch (\Exception $e) {
            return \response()->json(['message' => $e->getMessage()], 500);
        }
        return \response()->json([
            'success' => true,
            'message' => 'Senha do Usuário atualizada!'
        ], 201);
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        try {
            $data = $this->service->get([
                'with' => ['tipo_usuario'],
                'paginate' => $request['paginate'] === "true",
                'perPage' => $request['perPage'],
                'page' => $request['page'],
                'search' => json_decode($request['search'], true),
            ]);

            return \response()->json($data, 200);
        } catch (\Exception $e) {
            return \response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @param  string  $user
     * @return JsonResponse
     */
    public function edit(string $user)
    {
        $user = $this->service->find($user);
        $user->endereco = $user->endereco;
        $user->tipo_usuario = $user->tipo_usuario;
        return \response()->json([
            'registro' => $user,
        ], 201);
    }

    /**
     * @param  string  $user
     * @param  bool  $block
     * @return JsonResponse
     */
    public function block(string $user,bool $block) : JsonResponse
    {
        $user = $this->service->find($user);
        try{
            $data = $this->service->blockObject($user, $block);
            if (!$data) {
                throw new \Exception('Não foi possível '.($block ? 'bloquear':'desbloquear').' o usuário');
            }
        } catch (\Exception $e){
            return \response()->json(['message' => $e->getMessage()], $e->getCode() !== 0 ? $e->getCode() : 500 );
        }
        return \response()->json([
            'data' => [
                'success' => true,
                'message' => 'Usuário '.($block ? 'bloqueado' : 'desbloqueado').' com sucesso!'
            ]
        ], 201);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function meusDados()
    {
        return view('modules.usuario.meus-dados');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function editarSenha()
    {
        return view('modules.usuario.mudar-senha');
    }

    /**
     * @param  string  $user
     * @return JsonResponse
     */
    public function editUser()
    {
        $user = Auth::user();
        $user->endereco = $user->endereco;
        $user->tipo_usuario = $user->tipo_usuario;
        return \response()->json([
            'registro' => $user,
        ], 201);
    }
}
