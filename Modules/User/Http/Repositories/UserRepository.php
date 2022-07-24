<?php

namespace Modules\User\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\User\Entities\User;

class UserRepository extends Repository
{
    public function __construct(User $entity)
    {
        $this->entity = $entity;
    }
}
