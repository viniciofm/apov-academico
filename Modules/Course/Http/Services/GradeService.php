<?php

namespace Modules\Course\Http\Services;

use App\Http\Services\Service;
use Modules\Course\Entities\Curso;
use Modules\Course\Http\Repositories\GradeRepository;

class GradeService extends Service
{
    public function __construct(GradeRepository $repository)
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

    /**
     * @param  Curso  $curso
     * @return mixed
     */
    public function allByCurso(Curso $curso)
    {
        return $this->repository->allByCurso($curso);
    }
}
