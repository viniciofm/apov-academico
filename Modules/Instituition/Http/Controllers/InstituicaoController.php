<?php

namespace Modules\Instituition\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Modules\Instituition\Http\Requests\InstituicaoRequestValidator;
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
     * @return Application|Factory|View
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
     * @return Application|Factory|View
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
