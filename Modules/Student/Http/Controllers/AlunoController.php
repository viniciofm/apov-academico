<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Student\Entities\Aluno;
use Modules\Student\Http\Requests\AlunoRequestValidator;
use Modules\Student\Http\Services\AlunoService;
use Modules\User\Http\Services\UserService;

class AlunoController extends Controller
{
    /**
     * @var AlunoService $service
     */
    protected $service;

    /**
     * @var UserService $userService
     */
    protected $userService;

    /**
     * @param  AlunoService  $service
     * @param  UserService  $userService
     */
    public function __construct(AlunoService $service, UserService $userService)
    {
        $this->service = $service;
        $this->userService = $userService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('modules.aluno.index');
    }

    /**
     * @param  AlunoRequestValidator  $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(AlunoRequestValidator $request)
    {
        $r = $request->all()['usuario'];
        //validar cadastro
        $canRegister = $this->userService->canRegisterCadastro($r, 'aluno');
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
            'message' => 'Aluno cadastrado!'
        ], 201);
    }

    /**
     * @param  AlunoRequestValidator  $request
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(AlunoRequestValidator $request, $id)
    {
        $r = $request->all()['usuario'];
        //validar atualização
        $registro = $this->service->find($id);
        $canRegister = $this->userService->canRegisterCadastro($r, 'aluno', $registro->user_id);
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
            'message' => 'Aluno atualizado!'
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
                'with' => ['usuario'],
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
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        try {
            $data = array_values($this->service->all(true)->toArray());
            return \response()->json($data, 200);
        } catch (\Exception $e) {
            return \response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @param  Aluno  $aluno
     * @return JsonResponse
     */
    public function edit(Aluno $aluno)
    {
        $aluno->endereco = $aluno->endereco;
        return \response()->json([
            'registro' => $aluno,
        ], 201);
    }

    /**
     * @param  Aluno  $aluno
     * @param  bool  $active
     * @return JsonResponse
     */
    public function active(Aluno $aluno,bool $active) : JsonResponse
    {
        try{
            $data = $this->service->activeObject($aluno, $active);
            if (!$data) {
                throw new \Exception('Não foi possível '.($active?'ativar':'desativar').' o aluno');
            }
        } catch (\Exception $e){
            return \response()->json(['message' => $e->getMessage()], $e->getCode() !== 0 ? $e->getCode() : 500 );
        }
        return \response()->json([
            'data' => [
                'success' => true,
                'message' => 'Aluno '.($active ? 'ativado' : 'desativado').' com sucesso!'
            ]
        ], 201);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function alunoIndex()
    {
        return view('modules.aluno.aluno');
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getMatriculas(Request $request): JsonResponse
    {
        try {
            $data = $this->service->getMatriculas([
                'with' => [],
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
}
