<?php

namespace Modules\Course\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\Course\Entities\Curso;
use Modules\Course\Entities\TurmaDisciplina;

class TurmaDisciplinaRepository extends Repository
{
    public function __construct(TurmaDisciplina $entity)
    {
        $this->entity = $entity;
    }
}
