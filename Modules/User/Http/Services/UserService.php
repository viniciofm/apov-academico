<?php

namespace Modules\User\Http\Services;

use App\Http\Services\Service;
use Modules\User\Http\Repositories\UserRepository;

class UserService extends Service
{
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
}
