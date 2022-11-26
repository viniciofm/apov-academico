<?php

namespace Modules\Course\Http\Repositories;

use App\Http\Repositories\Repository;
use Modules\Course\Entities\Cbo;
use Modules\Course\Entities\Curso;

class CboRepository extends Repository
{
    public function __construct(Cbo $entity)
    {
        $this->entity = $entity;
    }
}
