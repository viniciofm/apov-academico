<?php

namespace Modules\User\Http\Services;

use App\Http\Services\Service;
use Modules\User\Http\Repositories\TipoUsuarioRepository;

class TipoUsuarioService extends Service
{
    public function __construct(TipoUsuarioRepository $repository)
    {
        $this->repository = $repository;
    }
}
