<?php

namespace Modules\Content\Http\Services;

use App\Http\Services\Service;
use Modules\Content\Http\Repositories\NotaRepository;

class NotaService extends Service
{
    public function __construct(NotaRepository $repository)
    {
        $this->repository = $repository;
    }
}
