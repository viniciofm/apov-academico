<?php

namespace Modules\User\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\User\Entities\Estado;

class EstadoRepository extends Repository
{
    public function __construct(Estado $entity)
    {
        $this->entity = $entity;
    }
}
