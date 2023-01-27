<?php

namespace Modules\Content\Http\Services;

use App\Http\Services\Service;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Content\Entities\Atividade;
use Modules\Content\Entities\Aula;
use Modules\Content\Entities\Nota;
use Modules\Content\Http\Repositories\AulaRepository;
use Modules\Course\Entities\TurmaDisciplina;
use Modules\Student\Entities\Aluno;
use Modules\Student\Entities\TurmaDisciplinaMatricula;

class AulaService extends Service
{
    /**
     * @var PresencaService
     */
    private $presencaService;

    public function __construct(AulaRepository $repository, PresencaService $presencaService)
    {
        $this->repository = $repository;
        $this->presencaService = $presencaService;
    }

    /**
     * @param $request
     * @return int|null
     */
    public function create($request)
    {
        try {
            DB::beginTransaction();

            $count = 0;
            $attributes = $request->all();
            if($attributes['data']){
                $attributes['data'] = Carbon::createFromTimeString($attributes['data']);
            }
            $numero_aulas = (int) $attributes['numero_aulas'];
            for ($i = 0; $i < $numero_aulas; $i++){
                $registro =  $this->repository->create($attributes);
                if ($registro) $count++;
            }

            DB::commit();
            return $count;
        } catch(\Exception $e){
            DB::rollBack();
        }
        return null;
    }

    /**
     * @param  TurmaDisciplina  $turmaDisciplina
     * @return Arr
     */
    public function findDatesFromTurma(TurmaDisciplina $turmaDisciplina) : array
    {
        return array_values(array_unique($turmaDisciplina->aulas->sortBy('data')->pluck('data')->toArray()));
    }

    /**
     * @param  TurmaDisciplina  $turmaDisciplina
     * @param  string  $data
     * @return array
     */
    public function getClassesFromDate(TurmaDisciplina $turmaDisciplina, string $data) : array
    {
        $data = Carbon::createFromDate($data);

        $turmaDisciplina = TurmaDisciplina::with('matriculasTurma.matricula.aluno.usuario')->find($turmaDisciplina->id);

        $alunos = TurmaDisciplina::where('turma_disciplinas.id', ''.$turmaDisciplina->id.'')
            ->join('turma_disciplina_matricula', 'turma_disciplina_matricula.turma_disciplina_id', '=', 'turma_disciplinas.id')
            ->join('matriculas', 'matriculas.id', '=', 'turma_disciplina_matricula.matricula_id')
            ->join('alunos', 'matriculas.aluno_id', '=', 'alunos.id')
            ->join('users', 'alunos.user_id', '=', 'users.id')
            ->select(
                'users.nome',
                'alunos.matricula',
                'turma_disciplina_matricula.turma_disciplina_id',
                'turma_disciplina_matricula.matricula_id',
            )
            ->orderBy('users.nome')
            ->get();

        foreach ($alunos as &$aluno){
            $aluno->falta_aula = Aula::leftJoin('presencas', function ($join) use ($aluno) {
                    $join->on('presencas.aula_id', '=', 'aulas.id')
                        ->where('presencas.matricula_id', '=', "" .$aluno->matricula_id . "");
                })
                ->where('aulas.turma_disciplina_id', '=', "".$turmaDisciplina->id."")
                ->where('aulas.data', $data->format('Y-m-d'))
                ->select(
                'aulas.*', 'presencas.id as presenca_id', 'presencas.falta'
            )->orderBy('aulas.id')->get();
        }

        return $alunos->toArray();
    }

    /**
     * @param  TurmaDisciplina  $turmaDisciplina
     * @param  array  $faltas
     * @return bool
     */
    public function storeGrades(TurmaDisciplina $turmaDisciplina, array $faltas) : bool
    {
        foreach ($faltas as $falta){
            if ($falta['presenca_id'] && !$falta['falta']){
                //remover falta
                $presenca = $this->presencaService->find($falta['presenca_id']);
                $this->presencaService->remove($presenca);
            }elseif($falta['falta'] && !$falta['presenca_id']){
                //criar falta
                $this->presencaService->create([
                    'falta' => true,
                    'matricula_id' => $falta['matricula_id'],
                    'aula_id' => $falta['aula_id'],
                ]);
            }
        }

        return true;
    }
}
