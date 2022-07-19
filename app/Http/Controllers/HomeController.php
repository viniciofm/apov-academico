<?php

namespace App\Http\Controllers;

use Modules\Company\Http\Services\EmpresaService;

class HomeController extends Controller
{
    /**
     * @var EmpresaService
     */
    private $service;

    public function __construct(EmpresaService $service)
    {
        $this->service = $service;
    }


    /**
     * @return void
     */
    public function index()
    {

        dd($this->service->all());
    }
}
