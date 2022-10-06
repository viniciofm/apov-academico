<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\Curso;
use Modules\Course\Entities\Grade;
use Modules\Course\Http\Requests\CursoRequestValidator;
use Modules\Course\Http\Requests\GradeRequestValidator;
use Modules\Course\Http\Services\CursoService;
use Modules\Course\Http\Services\GradeService;
use Modules\User\Http\Services\UserService;

class GradeController extends Controller
{
    /**
     * @var GradeService
     */
    protected $service;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @param  GradeService  $service
     * @param  UserService  $userService
     */
    public function __construct(GradeService $service, UserService $userService)
    {
        $this->service = $service;
        $this->userService = $userService;
    }

    /**
     * @param  GradeRequestValidator  $request
     * @return JsonResponse
     */
    public function store(GradeRequestValidator $request)
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
            'message' => 'Grade cadastrada!'
        ], 201);
    }

    /**
     * @param  GradeRequestValidator  $request
     * @param $id
     * @return JsonResponse
     */
    public function update(GradeRequestValidator $request, $id)
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
            'message' => 'Grade atualizada!'
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
            ]);

            return \response()->json($data, 200);
        } catch (\Exception $e) {
            return \response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @param  Curso  $curso
     * @return \Illuminate\Http\JsonResponse
     */
    public function getById(Grade $grade)
    {
        return \response()->json([
            'registro' => ['grade' => $grade, 'curso' => $grade->curso],
        ], 201);
    }

    /**
     * @param  Grade  $grade
     * @return JsonResponse
     */
    public function edit(Grade $grade)
    {
        return \response()->json([
            'registro' => $grade,
        ], 201);
    }

    /**
     * @param  Grade  $grade
     * @param  bool  $active
     * @return JsonResponse
     */
    public function active(Grade $grade,bool $active) : JsonResponse
    {
        try{
            $data = $this->service->activeObject($grade, $active);
            if (!$data) {
                throw new \Exception('Não foi possível '.($active?'ativar':'desativar').' a grade');
            }
        } catch (\Exception $e){
            return \response()->json(['message' => $e->getMessage()], $e->getCode() !== 0 ? $e->getCode() : 500 );
        }
        return \response()->json([
            'data' => [
                'success' => true,
                'message' => 'Grade '.($active ? 'ativada' : 'desativada').' com sucesso!'
            ]
        ], 201);
    }
}
