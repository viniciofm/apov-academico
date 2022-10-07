<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\Curso;
use Modules\Course\Entities\Disciplina;
use Modules\Course\Entities\Grade;
use Modules\Course\Http\Requests\DisciplinaRequestValidator;
use Modules\Course\Http\Requests\GradeRequestValidator;
use Modules\Course\Http\Services\DisciplinaService;
use Modules\Course\Http\Services\GradeService;
use Modules\User\Http\Services\UserService;

class DisciplinaController extends Controller
{

    /**
     * @var DisciplinaService
     */
    protected $service;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @param  DisciplinaService  $service
     * @param  UserService  $userService
     */
    public function __construct(DisciplinaService $service, UserService $userService)
    {
        $this->service = $service;
        $this->userService = $userService;
    }

    /**
     * @param  DisciplinaRequestValidator  $request
     * @return JsonResponse
     */
    public function store(DisciplinaRequestValidator $request)
    {
        try {
            //validar cadastro
            $canRegister = $this->service->canRegisterCadastro($request->get('sigla'), $request->get('grade_id'));
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
            'message' => 'Disciplina cadastrada!'
        ], 201);
    }

    /**
     * @param  DisciplinaRequestValidator  $request
     * @param $id
     * @return JsonResponse
     */
    public function update(DisciplinaRequestValidator $request, $id)
    {
        try {
            //validar atualização
            $canRegister = $this->service->canRegisterCadastro($request->get('sigla'), $request->get('grade_id'), $id);
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
            'message' => 'Disciplina atualizada!'
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
                'with' => [],
                'paginate' => $request['paginate'] === "true",
                'perPage' => $request['perPage'],
                'page' => $request['page'],
                'search' => json_decode($request['search'], true),
                'orderBy' => 'sigla',
                'orderByOrder' => 'asc',
            ]);

            return \response()->json($data, 200);
        } catch (\Exception $e) {
            return \response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @param  Disciplina  $disciplina
     * @return JsonResponse
     */
    public function edit(Disciplina $disciplina)
    {
        return \response()->json([
            'registro' => $disciplina,
        ], 201);
    }
}
