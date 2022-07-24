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
}
