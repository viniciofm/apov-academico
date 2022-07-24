<?php

namespace Modules\Course\Http\Services;

use App\Http\Services\Service;
use Modules\Course\Http\Repositories\TurmaDisciplinaRepository;

class TurmaDisciplinaService extends Service
{
    public function __construct(TurmaDisciplinaRepository $repository)
    {
        $this->repository = $repository;
    }
}
