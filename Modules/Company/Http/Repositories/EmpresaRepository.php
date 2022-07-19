<?php

namespace Modules\Company\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\Company\Entities\Empresa;

class EmpresaRepository extends Repository
{
    public function __construct(Empresa $entity)
    {
        $this->entity = $entity;
    }
}
