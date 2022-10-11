<?php

namespace Modules\Student\Http\Services;

use App\Http\Services\Service;
use Illuminate\Support\Facades\DB;
use Modules\Course\Http\Services\TurmaDisciplinaService;
use Modules\Student\Http\Repositories\MatriculaRepository;

class MatriculaService extends Service
{
    /**
     * @var TurmaDisciplinaService $turmaDisciplinaService
     */
    protected $turmaDisciplinaService;

    /**
     * @var TurmaDisciplinaMatriculaService
     */
    protected $turmaDisciplinaMatriculaService;

    public function __construct(MatriculaRepository $repository, TurmaDisciplinaService $turmaDisciplinaService, TurmaDisciplinaMatriculaService $turmaDisciplinaMatriculaService)
    {
        $this->repository = $repository;
        $this->turmaDisciplinaService = $turmaDisciplinaService;
        $this->turmaDisciplinaMatriculaService = $turmaDisciplinaMatriculaService;
    }

    /**
     * @param  array  $attributes
     * @return mixed|null
     */
    public function create(array $attributes)
    {
        try {
            DB::beginTransaction();
            $attributes['status'] = 'matriculado';
            $matricula = $this->repository->create($attributes);
            //registro das disciplinas
            foreach ($attributes['disciplinas'] as $disciplinaId){
                //buscar turma_disciplina
                $turmaDisciplina = $this->turmaDisciplinaService->whereFunc(function($q) use ($matricula, $disciplinaId) {
                    $q->where('turma_id', $matricula->turma_id)->where('disciplina_id', $disciplinaId);
                })->first();
                if ($turmaDisciplina) {
                    $this->turmaDisciplinaMatriculaService->create(
                        ['turma_disciplina_id' => $turmaDisciplina->id, 'matricula_id' => $matricula->id, 'status' => 'matriculado']
                    );
                }
            }
            DB::commit();
            return $matricula;
        } catch(\Exception $e){
            DB::rollBack();
        }
        return null;
    }

    /**
     * @param  array  $attributes
     * @return mixed|null
     */
    public function addDisciplinas(array $attributes)
    {
        try {
            DB::beginTransaction();
            $matricula = $this->repository->find($attributes['matricula_id']);
            //registro das disciplinas
            foreach ($attributes['disciplinas'] as $disciplinaId){
                //buscar turma_disciplina
                $turmaDisciplina = $this->turmaDisciplinaService->whereFunc(function($q) use ($matricula, $disciplinaId,$attributes) {
                    $q->where('turma_id', $attributes['turma_id'])->where('disciplina_id', $disciplinaId);
                })->first();

                if ($turmaDisciplina) {
                    //checar se já possui a turma_disciplina
                    $recorrente = $matricula->disciplinasMatricula->where('turma_disciplina_id', $turmaDisciplina->id);
                    if ($recorrente->count() > 0){
                        throw new \Exception('Disciplina '. $turmaDisciplina->disciplina->sigla .' já está vinculada a matrícula!', 301);
                    }

                    $this->turmaDisciplinaMatriculaService->create(
                        ['turma_disciplina_id' => $turmaDisciplina->id, 'matricula_id' => $matricula->id, 'status' => 'matriculado']
                    );
                }
            }
            DB::commit();
            return $matricula;
        } catch(\Exception $e){
            DB::rollBack();
            return $e;
        }
        return null;
    }

    /**
     * @param  array  $attributes
     * @return \Exception|mixed
     */
    public function deleteDisciplina(array $attributes)
    {
        try {
            DB::beginTransaction();
            $matricula = $this->repository->find($attributes['matricula_id']);
            $turmaDisciplinaMatricula = $this->turmaDisciplinaMatriculaService->find($attributes['id']);
            $this->turmaDisciplinaMatriculaService->remove($turmaDisciplinaMatricula);
            DB::commit();
            return $matricula;
        } catch(\Exception $e){
            DB::rollBack();
            return $e;
        }
        return null;
    }
}
