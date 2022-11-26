<?php

namespace Modules\Course\Http\Services;

use App\Http\Services\Service;
use Modules\Course\Http\Repositories\CboRepository;
use Modules\Course\Http\Repositories\CursoRepository;

class CboService extends Service
{
    public function __construct(CboRepository $repository)
    {
        $this->repository = $repository;
    }
}
