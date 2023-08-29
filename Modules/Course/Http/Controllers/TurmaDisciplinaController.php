<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Course\Entities\TurmaDisciplina;
use Modules\Course\Http\Requests\TurmaDisciplinaProfessorRequestValidator;
use Modules\Course\Http\Services\TurmaDisciplinaService;

class TurmaDisciplinaController extends Controller
{
    /**
     * @var TurmaDisciplinaService $service
     */
    protected $service;

    /**
     * @param  TurmaDisciplinaService  $service
     */
    public function __construct(TurmaDisciplinaService $service)
    {
        $this->service = $service;
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function get(Request $request)
    {
        try {
            $data = $this->service->get([
                'with' => ['disciplina', 'turma', 'professor.usuario'],
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
     * @param  TurmaDisciplinaProfessorRequestValidator  $request
     * @param $id
     * @return JsonResponse
     */
    public function updateProfessor(TurmaDisciplinaProfessorRequestValidator $request, $id)
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
            'message' => 'Professor atualizado!'
        ], 201);
    }

    /**
     * @param  TurmaDisciplina  $turmaDisciplina
     * @return JsonResponse
     */
    public function getById(TurmaDisciplina $turmaDisciplina)
    {
        $turmaDisciplina->turma = $turmaDisciplina->turma;
        $turmaDisciplina->disciplina = $turmaDisciplina->disciplina;
        return \response()->json([
            'registro' => $turmaDisciplina,
        ], 201);
    }

    /**
     * @param  TurmaDisciplina  $turmaDisciplina
     * @param  bool  $active
     * @return JsonResponse
     */
    public function active(TurmaDisciplina $turmaDisciplina,bool $active) : JsonResponse
    {
        try{
            $data = $this->service->activeObject($turmaDisciplina, $active);
            if (!$data) {
                throw new \Exception('Não foi possível '.($active?'ativar':'desativar').' a turma disciplina');
            }

            //atualizar status dos alunos em caso de inativação (conclusão) da turma disciplina
            TurmaDisciplinaService::updateStatusAlunos($turmaDisciplina);
        } catch (\Exception $e){
            return \response()->json(['message' => $e->getMessage()], $e->getCode() !== 0 ? $e->getCode() : 500 );
        }
        return \response()->json([
            'data' => [
                'success' => true,
                'message' => 'Turma disciplina '.($active ? 'ativada' : 'desativada').' com sucesso!'
            ]
        ], 201);
    }
}
