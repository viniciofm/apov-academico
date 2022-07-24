<?php

namespace Modules\User\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\User\Entities\TipoUsuario;

class TipoUsuarioRepository extends Repository
{
    public function __construct(TipoUsuario $entity)
    {
        $this->entity = $entity;
    }
}
