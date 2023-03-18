<?php

namespace Modules\Course\Http\Services;

use App\Http\Services\Service;
use Illuminate\Support\Facades\Auth;
use Modules\Course\Entities\Grade;
use Modules\Course\Entities\TurmaDisciplina;
use Modules\Course\Http\Repositories\TurmaRepository;
use Modules\Student\Entities\Matricula;

class ReportService extends Service
{
    /**
     * @param  TurmaDisciplina  $turmaDisciplina
     * @return array
     */
    public function getDataTurmaDisciplina(TurmaDisciplina $turmaDisciplina = null, Matricula $matricula = null): array
    {
        if (!empty($turmaDisciplina)) {
            $matriculasTurma = $turmaDisciplina->matriculasTurma->sortBy('matricula.aluno.usuario.nome');
            $matricula = null;
        }elseif(!empty($matricula)){
            $disciplinasMatricula = $matricula->disciplinasMatricula->where('matricula_id', $matricula->id);

            foreach($disciplinasMatricula as $disciplinaMatricula){
                foreach($disciplinaMatricula->turmaDisciplina->aulas as $aula){
                    $falta = $aula->faltas->where('matricula_id', $matricula->id)->first();
//                    $disciplinaMatricula->faltas += $falta ? 1 : 0;
                }
            }

            $turmaDisciplina = null;
            $matriculasTurma = [];
        }

        foreach ($matriculasTurma as $matriculaTurma){
            foreach($turmaDisciplina->aulas as $aula){
                $falta = $aula->faltas->where('matricula_id', $matriculaTurma->matricula_id)->first();
//                $matriculaTurma->faltas += $falta ? 1 : 0;
            }
        }
        return compact('turmaDisciplina' , 'matriculasTurma', 'matricula');
    }

    /**
     * @param  Matricula  $matricula
     * @return array
     */
    public function getDataByMatricula(Matricula $matricula): array
    {
        $matriculasTurma = $matricula->disciplinasMatricula->sortBy('matricula.aluno.usuario.nome');

        $cargaHorariaCursada = 0;
        foreach ($matriculasTurma as &$matriculaTurma) {
            $turmaDisciplina = $matriculaTurma->turmaDisciplina;
            $matriculaTurma->numAulas = count($matriculaTurma->turmaDisciplina->aulas);
            foreach ($turmaDisciplina->aulas as $aula) {
                $falta = $aula->faltas->where('matricula_id', $matriculaTurma->matricula_id)->first();
                $matriculaTurma->faltas += $falta ? 1 : 0;
            }
            foreach ($turmaDisciplina->atividades as $atividade) {
                $nota = $atividade->notas->where('matricula_id', $matriculaTurma->matricula_id)->first();
                $matriculaTurma->nota += $nota->nota ?? 0;
            }

            if ($matriculaTurma->status === 'aprovado') {
                $cargaHorariaCursada += $matriculaTurma->turmaDisciplina->disciplina->carga_horaria;
            }
        }

        return compact( 'matriculasTurma', 'cargaHorariaCursada');
    }
}
