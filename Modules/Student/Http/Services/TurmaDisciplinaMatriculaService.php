<?php

namespace Modules\Student\Http\Services;

use App\Http\Services\Service;
use Modules\Course\Http\Services\TurmaDisciplinaService;
use Modules\Student\Http\Repositories\MatriculaRepository;
use Modules\Student\Http\Repositories\TurmaDisciplinaMatriculaRepository;

class TurmaDisciplinaMatriculaService extends Service
{
    /**
     * @var TurmaDisciplinaService $turmaDisciplinaService
     */
    protected TurmaDisciplinaService $turmaDisciplinaService;

    public function __construct(TurmaDisciplinaMatriculaRepository $repository)
    {
        $this->repository = $repository;
    }
}
