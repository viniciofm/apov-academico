<?php

namespace Modules\User\Http\Services;

use App\Http\Services\Service;
use Modules\User\Http\Repositories\EstadoRepository;

class EstadoService extends Service
{
    public function __construct(EstadoRepository $repository)
    {
        $this->repository = $repository;
    }
}
