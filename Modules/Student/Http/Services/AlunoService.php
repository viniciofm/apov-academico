<?php

namespace Modules\Student\Http\Services;

use App\Http\Services\Service;
use Modules\Student\Http\Repositories\AlunoRepository;

class AlunoService extends Service
{
    public function __construct(AlunoRepository $repository)
    {
        $this->repository = $repository;
    }
}
