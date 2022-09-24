<?php

namespace Modules\User\Http\Services;

use App\Http\Services\Service;
use Modules\User\Http\Repositories\UserRepository;

class UserService extends Service
{
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param  array  $request
     * @param  string  $nomeTipoUsuario
     * @return bool
     */
    public function canRegisterCadastro(array $request, string $nomeTipoUsuario, string $userIdUpdate = NULL) : bool
    {
        return $this->repository->canRegisterCadastro($request, $nomeTipoUsuario, $userIdUpdate);
    }
}
