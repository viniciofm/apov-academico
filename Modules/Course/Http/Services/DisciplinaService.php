<?php

namespace Modules\Course\Http\Services;

use App\Http\Services\Service;
use Modules\Course\Http\Repositories\DisciplinaRepository;

class DisciplinaService extends Service
{
    public function __construct(DisciplinaRepository $repository)
    {
        $this->repository = $repository;
    }
}
