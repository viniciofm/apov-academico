<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Course\Entities\Disciplina;
use Modules\Course\Entities\Turma;
use Modules\Course\Http\Requests\DisciplinaRequestValidator;
use Modules\Course\Http\Services\DisciplinaService;
use Modules\Course\Http\Services\TurmaDisciplinaService;
use Modules\User\Http\Services\UserService;

class DisciplinaController extends Controller
{

    /**
     * @var DisciplinaService $service
     */
    protected $service;

    /**
     * @var UserService $userService
     */
    protected $userService;

    /**
     * @var TurmaDisciplinaService $turmaDisciplinaService
     */
    protected $turmaDisciplinaService;

    /**
     * @param  DisciplinaService  $service
     * @param  UserService  $userService
     * @param  TurmaDisciplinaService  $turmaDisciplinaService
     */
    public function __construct(DisciplinaService $service, UserService $userService, TurmaDisciplinaService $turmaDisciplinaService)
    {
        $this->service = $service;
        $this->userService = $userService;
        $this->turmaDisciplinaService = $turmaDisciplinaService;
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
                throw new \Exception('Verifique os dados informados: Os dados já encontram-se registrados na instituição!');
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
                throw new \Exception('Verifique os dados informados: Os dados já encontram-se registrados na instituição!');
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
    public function delete(Request $request): JsonResponse
    {
        try {
            $registro = $this->service->find($request->id);

            //checa se existem turmas vinculadas com a disciplina
            if(count($registro->turmaDisciplinas)){
                throw new \Exception('Não é possível remover essa disciplina. Existem ' . count($registro->turmaDisciplinas) . ' turma(s) vinculada(s).');
            }

            $data = $this->service->remove($registro);

            if (!$data) {
                throw new \Exception('Não foi possível remover o item!');
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

    /**
     * @param  Turma  $turma
     * @return JsonResponse
     */
    public function allByTurma(Turma $turma)
    {
        try {
            $data = $this->service->whereFunc(function($q) use ($turma) {
                $q->whereHas('turmaDisciplinas', function($qq) use ($turma) {
                    $qq->where('turma_id', $turma->id)->where('ativo', true);
                });
            });

            return \response()->json($data, 200);
        } catch (\Exception $e) {
            return \response()->json($e->getMessage(), 500);
        }
    }
}
