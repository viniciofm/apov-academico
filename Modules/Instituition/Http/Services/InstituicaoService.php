<?php

namespace Modules\Instituition\Http\Services;

use App\Http\Services\Service;
use Illuminate\Support\Facades\Auth;
use Modules\Instituition\Entities\Instituicao;
use Modules\Instituition\Http\Repositories\InstituicaoRepository;

class InstituicaoService extends Service
{
    public function __construct(InstituicaoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getByUser() :? Instituicao
    {
        $usuario = Auth::user();
        $instituicao = $usuario->instituicao;
        $instituicao->endereco = $instituicao->endereco;

        return $instituicao;
    }
}
