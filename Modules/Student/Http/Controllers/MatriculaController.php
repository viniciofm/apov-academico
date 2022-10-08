<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Student\Http\Requests\MatriculaRequestValidator;
use Modules\Student\Http\Services\AlunoService;
use Modules\Student\Http\Services\MatriculaService;
use Modules\User\Http\Services\UserService;

class MatriculaController extends Controller
{
    /**
     * @var MatriculaService $service
     */
    protected $service;

    /**
     * @var UserService $userService
     */
    protected $userService;

    /**
     * @param  MatriculaService  $service
     * @param  UserService  $userService
     */
    public function __construct(MatriculaService $service, UserService $userService)
    {
        $this->service = $service;
        $this->userService = $userService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('modules.matricula.index');
    }

    /**
     * @param  CursoRequestValidator  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(MatriculaRequestValidator $request)
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
            'message' => 'Matrícula registrada!'
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
                'with' => ['turma', 'turma.grade', 'aluno.usuario', 'curso'],
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
