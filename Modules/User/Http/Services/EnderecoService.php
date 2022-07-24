<?php

namespace Modules\User\Http\Services;

use App\Http\Services\Service;
use Modules\User\Http\Repositories\EnderecoRepository;

class EnderecoService extends Service
{
    public function __construct(EnderecoRepository $repository)
    {
        $this->repository = $repository;
    }
}
