<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Company\Entities\Empresa;
use Modules\Company\Http\Requests\EmpresaRequestValidator;
use Modules\Company\Http\Services\EmpresaService;
use Modules\User\Http\Services\UserService;

class EmpresaController extends Controller
{
    /**
     * @var EmpresaService $service
     */
    protected $service;

    /**
     * @var UserService $userService
     */
    protected $userService;

    /**
     * @param  EmpresaService  $service
     * @param  UserService  $userService
     */
    public function __construct(EmpresaService $service, UserService $userService)
    {
        $this->service = $service;
        $this->userService = $userService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('modules.empresa.index');
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        try {
            $data = $this->service->all(true, 'nome');

            return \response()->json($data, 200);
        } catch (\Exception $e) {
            return \response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @param  EmpresaRequestValidator  $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(EmpresaRequestValidator $request)
    {
        $r = $request->all();
        //validar cadastro
        $canRegister = $this->userService->canRegisterCadastro($r, 'empresa');
        if (!$canRegister){
            throw new \Exception('Verfique os dados informados: Os dados já encontram-se registrados na instituição!');
        }

        try {
            $data = $this->service->create($request);

            if (!$data) {
                throw new \Exception('Não foi possível registrar o novo item!');
            }
        } catch (\Exception $e) {
            return \response()->json(['message' => $e->getMessage()], 500);
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
                'search' => json_decode($request['search'], true),
            ]);

            return \response()->json($data, 200);
        } catch (\Exception $e) {
            return \response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @param  Empresa  $empresa
     * @return void
     */
    public function edit(Empresa $empresa)
    {
        $empresa->endereco = $empresa->endereco;
        return \response()->json([
            'registro' => $empresa,
        ], 201);
    }

    /**
     * @param  EmpresaRequestValidator  $request
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(EmpresaRequestValidator $request, $id)
    {
        $r = $request->all();
        //validar atualização
        $registro = $this->service->find($id);
        $canRegister = $this->userService->canRegisterCadastro($r, 'empresa', $registro->user_id);
        if (!$canRegister){
            throw new \Exception('Verfique os dados informados: Os dados já encontram-se registrados na instituição!');
        }

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
            'message' => 'Empresa atualizada!'
        ], 201);
    }

    /**
     * @param ObjectId $providerId
     * @return JsonResponse
     */
    public function active(Empresa $empresa,bool $active) : JsonResponse
    {
        try{
            $data = $this->service->activeObject($empresa, $active);
            if (!$data) {
                throw new \Exception('Não foi possível '.($active?'ativar':'desativar').' a empresa');
            }
        } catch (\Exception $e){
            return \response()->json(['message' => $e->getMessage()], $e->getCode() !== 0 ? $e->getCode() : 500 );
        }
        return \response()->json([
            'data' => [
                'success' => true,
                'message' => 'Empresa '.($active ? 'ativada' : 'desativada').' com sucesso!'
            ]
        ], 201);
    }
}
