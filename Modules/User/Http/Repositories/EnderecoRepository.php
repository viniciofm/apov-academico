<?php

namespace Modules\User\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\User\Entities\Endereco;

class EnderecoRepository extends Repository
{
    public function __construct(Endereco $entity)
    {
        $this->entity = $entity;
    }
}
