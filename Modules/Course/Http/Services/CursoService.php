<?php

namespace Modules\Course\Http\Services;

use App\Http\Services\Service;
use Modules\Course\Http\Repositories\CursoRepository;

class CursoService extends Service
{
    public function __construct(CursoRepository $repository)
    {
        $this->repository = $repository;
    }
}
