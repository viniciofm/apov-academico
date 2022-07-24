<?php

namespace Modules\Course\Http\Services;

use App\Http\Services\Service;
use Modules\Course\Http\Repositories\GradeRepository;

class GradeService extends Service
{
    public function __construct(GradeRepository $repository)
    {
        $this->repository = $repository;
    }
}
