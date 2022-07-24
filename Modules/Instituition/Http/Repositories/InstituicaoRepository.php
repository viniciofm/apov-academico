<?php

namespace Modules\Instituition\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\Instituition\Entities\Instituicao;

class InstituicaoRepository extends Repository
{
    public function __construct(Instituicao $entity)
    {
        $this->entity = $entity;
    }
}
