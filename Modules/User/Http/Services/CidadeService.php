<?php

namespace Modules\User\Http\Services;

use App\Http\Services\Service;
use Modules\User\Http\Repositories\CidadeRepository;;

class CidadeService extends Service
{
    public function __construct(CidadeRepository $repository)
    {
        $this->repository = $repository;
    }
}
