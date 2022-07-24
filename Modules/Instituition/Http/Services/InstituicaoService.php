<?php

namespace Modules\Instituition\Http\Services;

use App\Http\Services\Service;
use Modules\Instituition\Http\Repositories\InstituicaoRepository;

class InstituicaoService extends Service
{
    public function __construct(InstituicaoRepository $repository)
    {
        $this->repository = $repository;
    }
}
