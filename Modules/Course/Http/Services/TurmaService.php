<?php

namespace Modules\Course\Http\Services;

use App\Http\Services\Service;
use Modules\Course\Http\Repositories\TurmaRepository;

class TurmaService extends Service
{
    public function __construct(TurmaRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param  string  $codigo
     * @param  string|null  $idUpdate
     * @return bool
     */
    public function canRegisterCadastro(string $codigo, string $idUpdate = NULL) : bool
    {
        return $this->repository->canRegisterCadastro($codigo, $idUpdate);
    }
}
