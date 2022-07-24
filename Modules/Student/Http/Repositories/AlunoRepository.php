<?php

namespace Modules\Student\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\Student\Entities\Aluno;

class AlunoRepository extends Repository
{
    public function __construct(Aluno $entity)
    {
        $this->entity = $entity;
    }
}
