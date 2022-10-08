<?php

namespace Modules\Teacher\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Company\Http\Requests\EmpresaRequestValidator;
use Modules\Teacher\Entities\Professor;
use Modules\Teacher\Http\Repositories\ProfessorRepository;
use Modules\Teacher\Http\Requests\ProfessorRequestValidator;
use Modules\Teacher\Http\Services\ProfessorService;
use Modules\User\Http\Services\UserService;

class ProfessorController extends Controller
{
    /**
     * @var ProfessorService $service
     */
    protected $service;

    /**
     * @var UserService $userService
     */
    protected $userService;

    /**
     * @param  ProfessorService  $service
     * @param  UserService  $userService
     */
    public function __construct(ProfessorService $service, UserService $userService)
    {
        $this->service = $service;
        $this->userService = $userService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('modules.professor.index');
    }

    /**
     * @param  ProfessorRequestValidator  $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(ProfessorRequestValidator $request)
    {
        $r = $request->all()['usuario'];
        //validar cadastro
        $canRegister = $this->userService->canRegisterCadastro($r, 'professor');
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
            'message' => 'Professor cadastrado!'
        ], 201);
    }

    /**
     * @param  ProfessorRequestValidator  $request
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(ProfessorRequestValidator $request, $id)
    {
        $r = $request->all()['usuario'];
        //validar atualização
        $registro = $this->service->find($id);
        $canRegister = $this->userService->canRegisterCadastro($r, 'professor', $registro->user_id);
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
            'message' => 'Professor atualizado!'
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
     * @param  Professor  $professor
     * @return JsonResponse
     */
    public function edit(Professor $professor)
    {
        $professor->endereco = $professor->endereco;
        return \response()->json([
            'registro' => $professor,
        ], 201);
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
     * @param  Professor  $registro
     * @param  bool  $active
     * @return JsonResponse
     */
    public function active(Professor $professor,bool $active) : JsonResponse
    {
        try{
            $data = $this->service->activeObject($professor, $active);
            if (!$data) {
                throw new \Exception('Não foi possível '.($active?'ativar':'desativar').' o professor');
            }
        } catch (\Exception $e){
            return \response()->json(['message' => $e->getMessage()], $e->getCode() !== 0 ? $e->getCode() : 500 );
        }
        return \response()->json([
            'data' => [
                'success' => true,
                'message' => 'Professor '.($active ? 'ativado' : 'desativado').' com sucesso!'
            ]
        ], 201);
    }
}
