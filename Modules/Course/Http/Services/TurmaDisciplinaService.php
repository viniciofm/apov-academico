<?php

namespace Modules\Course\Http\Services;

use App\Http\Services\Service;
use Modules\Course\Entities\TurmaDisciplina;
use Modules\Course\Http\Repositories\TurmaDisciplinaRepository;

class TurmaDisciplinaService extends Service
{
    public function __construct(TurmaDisciplinaRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param  TurmaDisciplina  $turmaDisciplina
     * @return void
     */
    public static function updateStatusAlunos(TurmaDisciplina $turmaDisciplina)
    {
        foreach($turmaDisciplina->matriculasTurma as $matricula){
            //calcular alunos da turma pela nota
            if($matricula->nota_final >= 60){
                $matricula->status = 'aprovado';
            }elseif ($matricula->status == 'aprovado' || !$turmaDisciplina->ativo){
                $matricula->status = 'reprovado';
            }

            //calcular alunos da turma pela frequência
            if($matricula->frequencia < 25 && $matricula->nota_final >= 60){
                $matricula->status = 'reprovado_falta';
            }

            $matricula->save();
        }
    }
}
