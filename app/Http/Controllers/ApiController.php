<?php

namespace App\Http\Controllers;

use Modules\User\Http\Services\CidadeService;
use Modules\User\Http\Services\EstadoService;

class ApiController extends Controller
{
    /**
     * @var CidadeService
     */
    private $service;

    public function __construct(EstadoService $service)
    {
        $this->service = $service;
    }

    /**
     * @return void
     */
    public function getCidades()
    {
        try {
            $data = $this->service->allWithCities();
            $message = __('Operation performed successfully');
            $code = 200;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
        }

        return response()->json([
            'message' => $message,
            'data' => isset($data) ? $data : null
        ], $code);
    }
}
