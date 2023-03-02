<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Student\Entities\Matricula;
use Modules\Student\Http\Requests\DisciplinaMatriculaRequestValidator;
use Modules\Student\Http\Requests\MatriculaRequestValidator;
use Modules\Student\Http\Requests\UpdateMatriculaRequestValidator;
use Modules\Student\Http\Services\MatriculaService;
use Modules\Student\Http\Services\TurmaDisciplinaMatriculaService;
use Modules\User\Http\Services\UserService;
use Carbon\Carbon;

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
     * @var TurmaDisciplinaMatriculaService $turmaDisciplinaMatriculaService
     */
    private $turmaDisciplinaMatriculaService;

    /**
     * @param  MatriculaService  $service
     * @param  UserService  $userService
     * @param  TurmaDisciplinaMatriculaService  $turmaDisciplinaMatriculaService
     */
    public function __construct(MatriculaService $service, UserService $userService, TurmaDisciplinaMatriculaService $turmaDisciplinaMatriculaService)
    {
        $this->service = $service;
        $this->turmaDisciplinaMatriculaService = $turmaDisciplinaMatriculaService;
        $this->userService = $userService;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('modules.matricula.index');
    }

    /**
     * @param  DisciplinaMatriculaRequestValidator  $request
     * @return JsonResponse
     */
    public function storeDisciplinas(DisciplinaMatriculaRequestValidator $request): JsonResponse
    {
        try {
            $data = $this->service->addDisciplinas($request->all());

            if ($data instanceof \Exception) {
                return \response()->json(['message' => $data->getMessage()], $data->getCode());
            }
            if (!$data) {
                throw new \Exception('Não foi possível registrar o novo item!');
            }
        } catch (\Exception $e) {
            return \response()->json(['message' => $e->getMessage()], 500);
        }
        return \response()->json([
            'success' => true,
            'message' => 'Disciplina(s) adicionada(s)!'
        ], 201);
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function deleteDisciplina(Request $request): JsonResponse
    {
        try {
            $data = $this->service->deleteDisciplina($request->all());

            if ($data instanceof \Exception) {
                return \response()->json(['message' => $data->getMessage()], $data->getCode());
            }
            if (!$data) {
                throw new \Exception('Não foi possível atualizar o item!');
            }
        } catch (\Exception $e) {
            return \response()->json(['message' => $e->getMessage()], 500);
        }
        return \response()->json([
            'success' => true,
            'message' => 'Disciplina removida!'
        ], 201);
    }

    /**
     * @param  UpdateMatriculaRequestValidator  $request
     * @return JsonResponse
     */
    public function store(MatriculaRequestValidator $request): JsonResponse
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
     * @param  UpdateMatriculaRequestValidator  $request
     * @return JsonResponse
     */
    public function update(UpdateMatriculaRequestValidator $request, string $id): JsonResponse
    {
        try {
            $request = $request->all();
            $request['motivo_cancelamento'] = $request['motivo_cancelamento'] ?? null;
            $request['data_saida'] = isset($request['data_saida']) ? Carbon::createFromTimeString($request['data_saida']) :  null;

            $data = $this->service->update($id, $request);

            if (!$data) {
                throw new \Exception('Não foi possível atualizar o item!');
            }
        } catch (\Exception $e) {
            return \response()->json(['message' => $e->getMessage()], 500);
        }
        return \response()->json([
            'success' => true,
            'message' => 'Matrícula atualizada!'
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
                'with' => ['turma', 'turma.grade', 'aluno.usuario:id,nome', 'curso', 'empresa'],
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
     * @param  Matricula  $matricula
     * @return JsonResponse
     */
    public function getById(Matricula $matricula): JsonResponse
    {
        $matricula = $this->service->findWith($matricula->id, ['aluno.usuario:id,nome','empresa', 'turma', 'curso']);

        return \response()->json([
            'registro' => $matricula,
        ], 201);
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getDisciplinas(Request $request): JsonResponse
    {
        try {
            $data = $this->turmaDisciplinaMatriculaService->get([
                'with' => [
                    'turmaDisciplina.disciplina:id,nome,sigla,carga_horaria',
                    'turmaDisciplina.turma:id,codigo'
                ],
                'paginate' => $request['paginate'] === "true",
                'perPage' => $request['perPage'],
                'page' => $request['page'],
                'search' => json_decode($request['search'], true),
            ]);

            return \response()->json($data, 200);
        } catch (\Exception $e) {
            return \response()->json($e->getMessage(), 500);
        }

        $matricula = $this->service->get($matricula->id, );
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getAlunos(Request $request): JsonResponse
    {
        try {
            $data = $this->turmaDisciplinaMatriculaService->get([
                'with' => [
                    'matricula.aluno.usuario:id,nome'
                ],
                'paginate' => $request['paginate'] === "true",
                'perPage' => $request['perPage'],
                'page' => $request['page'],
                'search' => json_decode($request['search'], true)
            ]);

            return \response()->json($data, 200);
        } catch (\Exception $e) {
            return \response()->json($e->getMessage(), 500);
        }

        $matricula = $this->service->get($matricula->id, );
    }
}
