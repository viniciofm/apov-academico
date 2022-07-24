<?php

namespace Modules\Student\Http\Services;

use App\Http\Services\Service;
use Modules\Student\Http\Repositories\MatriculaRepository;

class MatriculaService extends Service
{
    public function __construct(MatriculaRepository $repository)
    {
        $this->repository = $repository;
    }
}
