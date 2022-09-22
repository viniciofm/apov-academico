<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Company\Entities\Empresa;
use Modules\Company\Http\Requests\EmpresaRequestValidator;
use Modules\Company\Http\Services\EmpresaService;

class EmpresaController extends Controller
{
    /**
     * @var EmpresaService
     */
    protected $service;

    /**
     * @param  EmpresaRepository  $empresaRepository
     */
    public function __construct(EmpresaService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('modules.empresa.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('company::create');
    }

    /**
     * @param  EmpresaRequestValidator  $request
     * @return void
     */
    public function store(EmpresaRequestValidator $request)
    {
        try {
            $data = $this->service->create($request->all());

            if (!$data) {
                throw new \Exception('Não foi possível registrar o novo item!');
            }
        } catch (\Exception $e) {
            return \response()->json([$e->getMessage()], 500);
        }
        return \response()->json([
            'success' => true,
            'message' => 'Empresa cadastrada!'
        ], 201);
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        try {
            $data = $this->service->get([
                'with' => [],
                'paginate' => $request['paginate'] === "true",
                'perPage' => $request['perPage'],
                'page' => $request['page'],
                'column' => 'nome',
                'search' => $request['search'],
            ]);

            return \response()->json($data, 200);
        } catch (\Exception $e) {
            return \response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('company::edit');
    }

    /**
     * @param  EmpresaRequestValidator  $request
     * @param $id
     * @return void
     */
    public function update(EmpresaRequestValidator $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
