<?php

namespace Modules\Course\Http\Repositories;

use App\Http\Repositories\Repository;
use Modules\Course\Entities\Curso;

class CursoRepository extends Repository
{
    public function __construct(Curso $entity)
    {
        $this->entity = $entity;
    }
}
