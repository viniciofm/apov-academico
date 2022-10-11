<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\Curso;
use Modules\Course\Entities\Disciplina;
use Modules\Course\Entities\Grade;
use Modules\Course\Entities\Turma;
use Modules\Course\Http\Requests\DisciplinaRequestValidator;
use Modules\Course\Http\Requests\TurmaRequestValidator;
use Modules\Course\Http\Services\DisciplinaService;
use Modules\Course\Http\Services\TurmaService;
use Modules\User\Http\Services\UserService;

class TurmaController extends Controller
{
    /**
     * @var TurmaService
     */
    protected $service;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @param  TurmaService  $service
     * @param  UserService  $userService
     */
    public function __construct(TurmaService $service, UserService $userService)
    {
        $this->service = $service;
        $this->userService = $userService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('modules.turma.index');
    }

    /**
     * @param  TurmaRequestValidator  $request
     * @return JsonResponse
     */
    public function store(TurmaRequestValidator $request)
    {
        try {
            //validar cadastro
            $canRegister = $this->service->canRegisterCadastro($request->get('codigo'));
            if (!$canRegister){
                throw new \Exception('Verfique os dados informados: Os dados já encontram-se registrados na instituição!');
            }
            $data = $this->service->create($request->all());

            if (!$data) {
                throw new \Exception('Não foi possível registrar o novo item!');
            }
        } catch (\Exception $e) {
            return \response()->json(['message' => $e->getMessage()], 500);
        }
        return \response()->json([
            'success' => true,
            'message' => 'Turma cadastrada!'
        ], 201);
    }

    /**
     * @param  TurmaRequestValidator  $request
     * @param $id
     * @return JsonResponse
     */
    public function update(TurmaRequestValidator $request, $id)
    {
        try {
            //validar atualização
            $canRegister = $this->service->canRegisterCadastro($request->get('codigo'), $id);
            if (!$canRegister){
                throw new \Exception('Verfique os dados informados: Os dados já encontram-se registrados na instituição!');
            }
            $data = $this->service->update($id, $request->all());

            if (!$data) {
                throw new \Exception('Não foi possível atualizar o item!');
            }
        } catch (\Exception $e) {
            return \response()->json(['message' => $e->getMessage()], 500);
        }
        return \response()->json([
            'success' => true,
            'message' => 'Turma atualizada!'
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
                'with' => ['grade','grade.curso'],
                'paginate' => $request['paginate'] === "true",
                'perPage' => $request['perPage'],
                'page' => $request['page'],
                'search' => json_decode($request['search'], true),
                'orderBy' => 'codigo',
                'orderByOrder' => 'asc',
            ]);

            return \response()->json($data, 200);
        } catch (\Exception $e) {
            return \response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @param  Turma  $turma
     * @return JsonResponse
     */
    public function edit(Turma $turma)
    {
        $turma->grade->curso = $turma->grade->curso;
        return \response()->json([
            'registro' => $turma,
        ], 201);
    }

    /**
     * @param  Turma  $turma
     * @return JsonResponse
     */
    public function getById(Turma $turma)
    {
        return \response()->json([
            'registro' => $turma,
        ], 201);
    }

    /**
     * @param  Grade  $grade
     * @return JsonResponse
     */
    public function allByGrade(Grade $grade)
    {
        try {
            $data = $this->service->allByGrade($grade);

            return \response()->json($data, 200);
        } catch (\Exception $e) {
            return \response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @param  Turma  $turma
     * @param  bool  $active
     * @return JsonResponse
     */
    public function active(Turma $turma,bool $active) : JsonResponse
    {
        try{
            $data = $this->service->activeObject($turma, $active);
            if (!$data) {
                throw new \Exception('Não foi possível '.($active?'ativar':'desativar').' a turma');
            }
        } catch (\Exception $e){
            return \response()->json(['message' => $e->getMessage()], $e->getCode() !== 0 ? $e->getCode() : 500 );
        }
        return \response()->json([
            'data' => [
                'success' => true,
                'message' => 'Turma '.($active ? 'ativada' : 'desativada').' com sucesso!'
            ]
        ], 201);
    }
}
