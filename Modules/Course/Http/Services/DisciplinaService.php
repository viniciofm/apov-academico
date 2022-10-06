<?php

namespace Modules\Course\Http\Services;

use App\Http\Services\Service;
use Modules\Course\Http\Repositories\DisciplinaRepository;

class DisciplinaService extends Service
{
    public function __construct(DisciplinaRepository $repository)
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
