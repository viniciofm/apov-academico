<?php

namespace Modules\Course\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\Course\Entities\Curso;
use Modules\Course\Entities\Grade;

class GradeRepository extends Repository
{
    public function __construct(Grade $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @param  string  $codigo
     * @param  string|null  $idUpdate
     * @return bool
     */
    public function canRegisterCadastro(string $codigo, string $idUpdate = NULL) : bool
    {
        $recorrente = $this->where('codigo', '=', $codigo)->first();
        if ($recorrente && $recorrente->id != $idUpdate) {
            return false;
        }

        return true;
    }
}
