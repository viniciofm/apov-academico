<?php

namespace Modules\Company\Http\Services;

use App\Http\Services\Service;
use Modules\Company\Http\Repositories\EmpresaRepository;

class EmpresaService extends Service
{
    public function __construct(EmpresaRepository $repository)
    {
        $this->repository = $repository;
    }
}
