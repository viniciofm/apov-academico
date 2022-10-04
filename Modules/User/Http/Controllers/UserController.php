<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Entities\User;
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
        $r = $request->all()['usuario'];
        //validar cadastro
        $canRegister = $this->service->canRegisterCadastro($r, 'usuario');
        if (!$canRegister){
            throw new \Exception('Verfique os dados informados: Os dados já encontram-se registrados na instituição!');
        }

        try {
            $data = $this->service->create($request);

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
        $r = $request->all()['usuario'];
        //validar atualização
        $registro = $this->service->find($id);
        $canRegister = $this->service->canRegisterCadastro($r, 'usuario', $registro->user_id);
        if (!$canRegister){
            throw new \Exception('Verfique os dados informados: Os dados já encontram-se registrados na instituição!');
        }

        try {
            $data = $this->service->update($id, $request);

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
     * @param  Aluno  $aluno
     * @return JsonResponse
     */
    public function edit(User $user)
    {
        $user->endereco = $user->endereco;
        return \response()->json([
            'registro' => $user,
        ], 201);
    }

    /**
     * @param  User  $user
     * @param  bool  $block
     * @return JsonResponse
     */
    public function block(User $user,bool $block) : JsonResponse
    {
        try{
            $data = $this->service->activeObject($user, $active);
            if (!$data) {
                throw new \Exception('Não foi possível '.($block ? 'bloquear':'desbloquear').' o usuário');
            }
        } catch (\Exception $e){
            return \response()->json(['message' => $e->getMessage()], $e->getCode() !== 0 ? $e->getCode() : 500 );
        }
        return \response()->json([
            'data' => [
                'success' => true,
                'message' => 'Aluno '.($block ? 'bloqueado' : 'desbloqueado').' com sucesso!'
            ]
        ], 201);
    }
}
