<?php

namespace Modules\Content\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\Content\Entities\Aula;

class AulaRepository extends Repository
{
    public function __construct(Aula $entity)
    {
        $this->entity = $entity;
    }
}
