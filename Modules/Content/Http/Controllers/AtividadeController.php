<?php

namespace Modules\Content\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Content\Entities\Atividade;
use Modules\Content\Http\Requests\AtividadeRequestValidator;
use Modules\Content\Http\Services\AtividadeService;

class AtividadeController extends Controller
{
    /**
     * @var AtividadeService $service
     */
    private $service;

    public function __construct(AtividadeService $service){
        $this->service = $service;
    }

    /**
     * @param  AtividadeRequestValidator  $request
     * @return JsonResponse
     */
    public function store(AtividadeRequestValidator $request): JsonResponse
    {
        try {
            $data = $this->service->create($request);

            if (!$data) {
                throw new \Exception('Não foi possível registrar o novo item!');
            }
            if ($data instanceof \Exception){
                throw new \Exception($data->getMessage());
            }
        } catch (\Exception $e) {
            return \response()->json(['message' => $e->getMessage()], 500);
        }
        return \response()->json([
            'success' => true,
            'message' => 'Registro cadastrado!'
        ], 201);
    }

    /**
     * @param  AtividadeRequestValidator  $request
     * @param $id
     * @return JsonResponse
     */
    public function update(AtividadeRequestValidator $request, $id)
    {
        try {
            $data = $this->service->update($id, $request);

            if (!$data) {
                throw new \Exception('Não foi possível atualizar o item!');
            }
            if ($data instanceof \Exception){
                throw new \Exception($data->getMessage());
            }
        } catch (\Exception $e) {
            return \response()->json(['message' => $e->getMessage()], 500);
        }
        return \response()->json([
            'success' => true,
            'message' => 'Registro atualizado!'
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
                'orderBy' => 'data',
                'orderByOrder' => 'asc',
            ]);

            return \response()->json($data, 200);
        } catch (\Exception $e) {
            return \response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        try {
            $registro = $this->service->find($request->id);
            $data = $this->service->remove($registro);

            if (!$data) {
                throw new \Exception('Não foi possível remover o item!');
            }
        } catch (\Exception $e) {
            return \response()->json(['message' => $e->getMessage()], 500);
        }
        return \response()->json([
            'success' => true,
            'message' => 'Atividade removida!'
        ], 201);
    }

    /**
     * @param  Atividade  $atividade
     * @return JsonResponse
     */
    public function edit(Atividade $atividade): JsonResponse
    {
        return \response()->json([
            'registro' => $atividade,
        ], 201);
    }
}
