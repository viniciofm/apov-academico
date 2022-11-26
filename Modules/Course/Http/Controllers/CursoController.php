<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\Curso;
use Modules\Course\Http\Requests\CursoRequestValidator;
use Modules\Course\Http\Services\CboService;
use Modules\Course\Http\Services\CursoService;
use Modules\User\Http\Services\UserService;

class CursoController extends Controller
{
    /**
     * @var CursoService
     */
    protected $service;
    /**
     * @var CboService
     */
    protected $cboService;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @param  CursoService  $service
     * @param  UserService  $userService
     */
    public function __construct(CursoService $service, UserService $userService, CboService $cboService)
    {
        $this->service = $service;
        $this->cboService = $cboService;
        $this->userService = $userService;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('modules.curso.index');
    }

    /**
     * @param  CursoRequestValidator  $request
     * @return JsonResponse
     */
    public function store(CursoRequestValidator $request): JsonResponse
    {
        try {
            $data = $this->service->create($request->all());

            if (!$data) {
                throw new \Exception('Não foi possível registrar o novo item!');
            }
        } catch (\Exception $e) {
            return \response()->json(['message' => $e->getMessage()], 500);
        }
        return \response()->json([
            'success' => true,
            'message' => 'Curso cadastrado!'
        ], 201);
    }

    /**
     * @param  CursoRequestValidator  $request
     * @param $id
     * @return JsonResponse
     */
    public function update(CursoRequestValidator $request, $id): JsonResponse
    {
        try {
            $data = $this->service->update($id, $request->all());

            if (!$data) {
                throw new \Exception('Não foi possível atualizar o item!');
            }
        } catch (\Exception $e) {
            return \response()->json(['message' => $e->getMessage()], 500);
        }
        return \response()->json([
            'success' => true,
            'message' => 'Curso atualizado!'
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
                'orderBy' => 'nome',
                'orderByOrder' => 'asc',
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
            $data = $this->service->all(true, 'nome');

            return \response()->json($data, 200);
        } catch (\Exception $e) {
            return \response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function allCbo(): JsonResponse
    {
        try {
            $data = $this->cboService->all(true, 'nome');

            return \response()->json($data, 200);
        } catch (\Exception $e) {
            return \response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @param  Curso  $curso
     * @return \Illuminate\Http\JsonResponse
     */
    public function getById(Curso $curso)
    {
        $curso->cbo;
        return \response()->json([
            'registro' => $curso,
        ], 201);
    }


    /**
     * @param  Curso  $curso
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Curso $curso)
    {
        $curso->cbo;
        return \response()->json([
            'registro' => $curso,
        ], 201);
    }

    /**
     * @param  Curso  $curso
     * @param  bool  $active
     * @return JsonResponse
     */
    public function active(Curso $curso,bool $active) : JsonResponse
    {
        try{
            $data = $this->service->activeObject($curso, $active);
            if (!$data) {
                throw new \Exception('Não foi possível '.($active?'ativar':'desativar').' o curso');
            }
        } catch (\Exception $e){
            return \response()->json(['message' => $e->getMessage()], $e->getCode() !== 0 ? $e->getCode() : 500 );
        }
        return \response()->json([
            'data' => [
                'success' => true,
                'message' => 'Curso '.($active ? 'ativado' : 'desativado').' com sucesso!'
            ]
        ], 201);
    }
}
