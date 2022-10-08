<?php

namespace Modules\Student\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\Student\Entities\TurmaDisciplinaMatricula;

class TurmaDisciplinaMatriculaRepository extends Repository
{
    public function __construct(TurmaDisciplinaMatricula $entity)
    {
        $this->entity = $entity;
    }
}
