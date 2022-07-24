<?php

namespace Modules\Content\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\Content\Entities\Presenca;

class PresencaRepository extends Repository
{
    public function __construct(Presenca $entity)
    {
        $this->entity = $entity;
    }
}
