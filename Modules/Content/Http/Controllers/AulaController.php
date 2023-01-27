<?php

namespace Modules\Content\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Content\Entities\Aula;
use Modules\Content\Http\Requests\AulaRequestValidator;
use Modules\Content\Http\Services\AulaService;
use Modules\Course\Entities\TurmaDisciplina;

class AulaController extends Controller
{
    /**
     * @var AulaService $service
     */
    private $service;

    public function __construct(AulaService $service){
        $this->service = $service;
    }

    /**
     * @param  AulaRequestValidator  $request
     * @return JsonResponse
     */
    public function store(AulaRequestValidator $request): JsonResponse
    {
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
            'message' => 'Registro cadastrado!'
        ], 201);
    }

    /**
     * @param  AulaRequestValidator  $request
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(AulaRequestValidator $request, $id)
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
            'message' => 'Registro atualizado!'
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
                'orderBy' => 'data',
                'orderByOrder' => 'asc',
            ]);

            return \response()->json($data, 200);
        } catch (\Exception $e) {
            return \response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
        public function delete(Request $request): JsonResponse
    {
        try {
            $registro = $this->service->find($request->id);
            $data = $this->service->remove($registro);

            if (!$data) {
                throw new \Exception('Não foi possível remover o item!');
            }
        } catch (\Exception $e) {
            return \response()->json(['message' => $e->getMessage()], 500);
        }
        return \response()->json([
            'success' => true,
            'message' => 'Aula removida!'
        ], 201);
    }

    /**
     * @param  Aula  $aula
     * @return JsonResponse
     */
    public function edit(Aula $aula)
    {
        return \response()->json([
            'registro' => $aula,
        ], 201);
    }

    /**
     * @param  Aula  $aula
     * @return JsonResponse
     */
    public function dates(TurmaDisciplina $turma_disciplina)
    {
        $dates = $this->service->findDatesFromTurma($turma_disciplina);

        return \response()->json(
            $dates
        , 201);
    }

    /**
     * @param  Request  $request
     * @param  TurmaDisciplina  $turma_disciplina
     * @return JsonResponse
     */
    public function grades(Request $request,TurmaDisciplina $turma_disciplina)
    {
        $data = $request->post('data');
        $data = $this->service->getClassesFromDate($turma_disciplina, $data);

        return \response()->json($data, 201);
    }

    /**
     * @param  Request  $request
     * @param  TurmaDisciplina  $turmaDisciplina
     * @return JsonResponse
     */
    public function storeGrades(Request $request, TurmaDisciplina $turmaDisciplina)
    {
        try {
            $request = $request->all();
            $faltas = json_decode($request['faltas'] ?? '', true);
            $data = $this->service->storeGrades($turmaDisciplina, $faltas);

            if (!$data) {
                throw new \Exception('Não foi possível atualizar o item!');
            }
            if ($data instanceof \Exception){
                throw new \Exception($data->getMessage());
            }
        } catch (\Exception $e) {
            return \response()->json(['message' => $e->getMessage()], 500);
        }
        return \response()->json([
            'success' => true,
            'message' => 'Registros atualizados!'
        ], 201);
    }
}
