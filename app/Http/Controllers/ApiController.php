<?php

namespace App\Http\Controllers;

use Modules\User\Http\Services\CidadeService;
use Modules\User\Http\Services\EstadoService;
use Modules\User\Http\Services\GeneroService;

class ApiController extends Controller
{
    /**
     * @var EstadoService
     */
    private $estadoService;

    /**
     * @var GeneroService
     */
    private $generoService;

    public function __construct(EstadoService $estadoService, GeneroService $generoService)
    {
        $this->estadoService = $estadoService;
        $this->generoService = $generoService;
    }

    /**
     * @return void
     */
    public function getCidades()
    {
        try {
            $data = $this->estadoService->allWithCities();
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

    /**
     * @return void
     */
    public function getGeneros()
    {
        try {
            $data = $this->generoService->all(false,'nome');
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
