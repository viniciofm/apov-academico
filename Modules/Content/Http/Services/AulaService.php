<?php

namespace Modules\Content\Http\Services;

use App\Http\Services\Service;
use Modules\Content\Http\Repositories\AulaRepository;

class AulaService extends Service
{
    public function __construct(AulaRepository $repository)
    {
        $this->repository = $repository;
    }
}
