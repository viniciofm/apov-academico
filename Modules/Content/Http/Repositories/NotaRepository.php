<?php

namespace Modules\Content\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\Content\Entities\Nota;

class NotaRepository extends Repository
{
    public function __construct(Nota $entity)
    {
        $this->entity = $entity;
    }
}
