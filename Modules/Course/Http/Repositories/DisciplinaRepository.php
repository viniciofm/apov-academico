<?php

namespace Modules\Course\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\Course\Entities\Disciplina;

class DisciplinaRepository extends Repository
{
    public function __construct(Disciplina $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @param  string  $sigla
     * @param  string  $grade_id
     * @param  string|null  $idUpdate
     * @return bool
     */
    public function canRegisterCadastro(string $sigla, string $grade_id, string $idUpdate = NULL) : bool
    {
        $recorrente = $this->whereFunc(function($q) use ($sigla, $grade_id) {
            $q->where('sigla', '=', $sigla)->where('grade_id', '=', $grade_id);
        })->first();

        if ($recorrente && $recorrente->id != $idUpdate) {
            return false;
        }
        return true;
    }
}
