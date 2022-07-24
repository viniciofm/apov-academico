<?php

namespace Modules\Student\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\Student\Entities\Matricula;

class MatriculaRepository extends Repository
{
    public function __construct(Matricula $entity)
    {
        $this->entity = $entity;
    }
}
