<?php

namespace Modules\User\Http\Services;

use App\Http\Services\Service;
use Modules\User\Http\Repositories\GeneroRepository;

class GeneroService extends Service
{
    public function __construct(GeneroRepository $repository)
    {
        $this->repository = $repository;
    }
}
