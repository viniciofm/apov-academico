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
}
