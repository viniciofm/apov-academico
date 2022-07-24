<?php

namespace Modules\Teacher\Http\Services;

use App\Http\Services\Service;
use Modules\Teacher\Http\Repositories\ProfessorRepository;

class ProfessorService extends Service
{
    public function __construct(ProfessorRepository $repository)
    {
        $this->repository = $repository;
    }
}
