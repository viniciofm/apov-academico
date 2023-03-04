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

    /**
     * @param  array  $attributes
     * @return mixed
     * @throws Exception
     */
    public function create(array $attributes)
    {
        $attributes['numero'] = isset($attributes['numero']) ? (int)$attributes['numero'] : null;

        return $this->repository->create($attributes);
    }
}
