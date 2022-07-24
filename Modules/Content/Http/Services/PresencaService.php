<?php

namespace Modules\Content\Http\Services;

use App\Http\Services\Service;
use Modules\Content\Http\Repositories\PresencaRepository;

class PresencaService extends Service
{
    public function __construct(PresencaRepository $repository)
    {
        $this->repository = $repository;
    }
}
