<?php

namespace Modules\Course\Http\Services;

use App\Http\Services\Service;
use Illuminate\Support\Facades\Auth;
use Modules\Course\Entities\Grade;
use Modules\Course\Http\Repositories\TurmaRepository;

class TurmaService extends Service
{
    /**
     * @var TurmaDisciplinaService $turmaDisciplinaService
     */
    protected $turmaDisciplinaService;

    /**
     * @var DisciplinaService $disciplinaService
     */
    protected $disciplinaService;

    public function __construct(TurmaRepository $repository, TurmaDisciplinaService $turmaDisciplinaService, DisciplinaService $disciplinaService)
    {
        $this->repository = $repository;
        $this->turmaDisciplinaService = $turmaDisciplinaService;
        $this->disciplinaService = $disciplinaService;
    }

    /**
     * @param  string  $codigo
     * @param  string|null  $idUpdate
     * @return bool
     */
    public function canRegisterCadastro(string $codigo, string $idUpdate = NULL) : bool
    {
        return $this->repository->canRegisterCadastro($codigo, $idUpdate);
    }

    /**
     * @param  array  $attributes
     * @return mixed
     * @throws \Exception
     */
    public function create(array $attributes)
    {
        $turma = $this->repository->create($attributes);
        //registrar turmas disciplinas
        $disciplinas = $this->disciplinaService->where('grade_id', '=', $attributes['grade_id']);
        foreach($disciplinas as $disciplina)
        {
            $this->createTurmaDisciplina($disciplina, $turma);
        }

        return $turma;
    }

    /**
     * @param  string  $id
     * @param  array  $attributes
     * @return mixed
     * @throws \Exception
     */
    public function update(string $id, array $attributes)
    {
        $turma = $this->repository->update($id, $attributes);
        //atualizar turmas disciplinas
        $disciplinas = $this->disciplinaService->where('grade_id', '=', $attributes['grade_id']);
        foreach($disciplinas as $disciplina)
        {
            $disciplinas = $turma->disciplinas;
            if($disciplinas){
                $turmaDisciplina = $turma->disciplinas->where('disciplina_id', $disciplina->id);
                if($turmaDisciplina->count() == 0){
                    $this->createTurmaDisciplina($disciplina, $turma);
                }
            }
        }

        return $turma;
    }

    /**
     * @param $disciplina
     * @param $turma
     * @return mixed
     * @throws \Exception
     */
    public function createTurmaDisciplina($disciplina, $turma){
        return $this->turmaDisciplinaService->create([
            'disciplina_id' => $disciplina->id,
            'turma_id' => $turma->id,
        ]);
    }

    /**
     * @param  Grade  $grade
     * @return mixed
     */
    public function allByGrade(Grade $grade)
    {
        return $this->repository->allByGrade($grade);
    }
}
