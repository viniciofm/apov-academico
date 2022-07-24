<?php

namespace Modules\Teacher\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\Teacher\Entities\Professor;

class ProfessorRepository extends Repository
{
    public function __construct(Professor $entity)
    {
        $this->entity = $entity;
    }
}
