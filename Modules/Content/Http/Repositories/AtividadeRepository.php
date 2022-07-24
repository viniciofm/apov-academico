<?php

namespace Modules\Content\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\Content\Entities\Atividade;

class AtividadeRepository extends Repository
{
    public function __construct(Atividade $entity)
    {
        $this->entity = $entity;
    }
}
