<?php

namespace Modules\Instituition\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Instituition\Http\Services\InstituicaoService;

class InstituicaoController extends Controller
{
    /**
     * @var InstituicaoService $service
     */
    private $service;

    /**
     * @param  InstituicaoService  $service
     */
    public function __construct(InstituicaoService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit()
    {
        return view('modules.instituicao.edit');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getByUser()
    {
        try {
            $data = $this->service->getByUser();

            return \response()->json($data, 200);
        } catch (\Exception $e) {
            return \response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
