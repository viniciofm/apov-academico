<?php

namespace Modules\User\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\User\Entities\Genero;

class GeneroRepository extends Repository
{
    public function __construct(Genero $entity)
    {
        $this->entity = $entity;
    }
}
