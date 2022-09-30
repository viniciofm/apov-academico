<?php

namespace Modules\Instituition\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Instituition\Http\Requests\InstituicaoRequestValidator;
use Modules\Instituition\Http\Services\InstituicaoService;
use Modules\User\Http\Services\EnderecoService;

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
     * @param  InstituicaoRequestValidator  $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(InstituicaoRequestValidator $request, $id)
    {
        try {
            $data = $this->service->update($id, $request);

            if (!$data) {
                throw new \Exception('Não foi possível atualizar o item!');
            }
        } catch (\Exception $e) {
            return \response()->json(['message' => $e->getMessage()], 500);
        }
        return \response()->json([
            'success' => true,
            'message' => 'Instituição atualizada!'
        ], 201);
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
