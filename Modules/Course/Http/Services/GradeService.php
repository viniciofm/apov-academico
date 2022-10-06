<?php

namespace Modules\Course\Http\Services;

use App\Http\Services\Service;
use Modules\Course\Http\Repositories\GradeRepository;

class GradeService extends Service
{
    public function __construct(GradeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param  string  $sigla
     * @param  string  $grade_id
     * @param  string|null  $idUpdate
     * @return bool
     */
    public function canRegisterCadastro(string $sigla, string $grade_id, string $idUpdate = NULL) : bool
    {
        return $this->repository->canRegisterCadastro($sigla, $grade_id, $idUpdate);
    }
}
