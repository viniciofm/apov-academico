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
            }elseif (!$turmaDisciplina->ativo){
                $matricula->status = 'reprovado';
            }elseif($turmaDisciplina->ativo){
                $matricula->status = 'matriculado';
            }

            //calcular alunos da turma pela frequência
            if(!$turmaDisciplina->ativo && $matricula->frequencia < 25 && $matricula->nota_final >= 60){
                $matricula->status = 'reprovado_falta';
            }

            if (!$turmaDisciplina->ativo){
                if (empty($matricula->nota_final)){
                    $matricula->nota_final = 0;
                }

                if (empty($matricula->frequencia)){
                    $matricula->frequencia = 0;
                }
            }

            $matricula->save();
        }
    }
}
