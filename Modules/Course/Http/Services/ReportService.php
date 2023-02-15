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
    public function dataDiarioClasse(TurmaDisciplina $turmaDisciplina){
        $numAulas = count($turmaDisciplina->aulas);
        $matriculasTurma = $turmaDisciplina->matriculasTurma->sortBy('matricula.aluno.usuario.nome');

        foreach ($matriculasTurma as $matriculaTurma){
            foreach($turmaDisciplina->aulas as $aula){
                $falta = $aula->faltas->where('matricula_id', $matriculaTurma->matricula_id)->first();
                $matriculaTurma->faltas += $falta ? 1 : 0;
            }
            foreach($turmaDisciplina->atividades as $atividade){
                $nota = $atividade->notas->where('matricula_id', $matriculaTurma->matricula_id)->first();
                $matriculaTurma->nota += $nota->nota ?? 0;
            }
        }

        return compact('turmaDisciplina', 'numAulas', 'matriculasTurma');
    }
}
