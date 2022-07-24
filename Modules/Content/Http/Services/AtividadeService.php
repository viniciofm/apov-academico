<?php

namespace Modules\Content\Http\Services;

use App\Http\Services\Service;
use Modules\Content\Http\Repositories\AtividadeRepository;

class AtividadeService extends Service
{
    public function __construct(AtividadeRepository $repository)
    {
        $this->repository = $repository;
    }
}
