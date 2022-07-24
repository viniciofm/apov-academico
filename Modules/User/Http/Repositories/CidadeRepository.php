<?php

namespace Modules\User\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\User\Entities\Cidade;

class CidadeRepository extends Repository
{
    public function __construct(Cidade $entity)
    {
        $this->entity = $entity;
    }
}
