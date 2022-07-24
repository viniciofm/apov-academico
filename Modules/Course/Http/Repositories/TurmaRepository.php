<?php

namespace Modules\Course\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\Course\Entities\Turma;

class TurmaRepository extends Repository
{
    public function __construct(Turma $entity)
    {
        $this->entity = $entity;
    }
}
