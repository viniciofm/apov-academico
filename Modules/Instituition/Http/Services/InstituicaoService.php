<?php

namespace Modules\Instituition\Http\Services;

use App\Http\Services\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Instituition\Entities\Instituicao;
use Modules\Instituition\Http\Repositories\InstituicaoRepository;
use Modules\User\Http\Services\EnderecoService;

class InstituicaoService extends Service
{
    /**
     * @var EnderecoService $enderecoService
     */
    private $enderecoService;

    public function __construct(InstituicaoRepository $repository, EnderecoService $enderecoService)
    {
        $this->repository = $repository;
        $this->enderecoService = $enderecoService;
    }

    public function getByUser() :? Instituicao
    {
        $usuario = Auth::user();
        $instituicao = $usuario->instituicao;
        $instituicao->endereco = $instituicao->endereco;

        return $instituicao;
    }

    /**
     * @param $id
     * @param $attributes
     * @return mixed|null
     */
    public function update($id, $request)
    {
        try {
            DB::beginTransaction();

            $attributes = $request->all();
            $instituicao = $this->repository->find($id);

            if(!$attributes['endereco']['cidade_id']){
                $attributes['endereco']['cidade_id'] = NULL;
            }
            if(!$attributes['endereco']['numero']){
                $attributes['endereco']['numero'] = NULL;
            }

            if ($instituicao->endereco_id)
            {
                //atualiza endereço
                $endereco = $this->enderecoService->update($instituicao->endereco_id, $attributes['endereco']);
            } else {
                //criar endereço
                $endereco = $this->enderecoService->create($attributes['endereco']);
                $attributes['endereco_id'] = $endereco ? $endereco->id : null;
            }

            //save image
            if ($request->hasFile('logomarca')) {
                $attributes['logomarca'] = $this->saveLogoMarca($request, $instituicao);
            }
            //atualiza instituicao
            $instituicao =  $this->repository->update($id ,$attributes);
            DB::commit();

            return $instituicao;
        } catch(\Exception $e){
            DB::rollBack();
        }
        return null;
    }

    public function saveLogoMarca($request, $instituicao = NULL){
        //save image
        if ($request->hasFile('logomarca')) {
            $name = sprintf('%d%d%s', date('Y'), date('n') < 6 ? 1 : 2, bin2hex(random_bytes(6)));
            $extension = pathinfo($request->file('logomarca')->getClientOriginalName(), PATHINFO_EXTENSION);
            if ($instituicao && $instituicao->logomarca) {
                Storage::disk('public')->delete(explode(env('APP_URL').Storage::url(''),$instituicao->logomarca)[1]);
            }

            $fileSaved = Storage::disk('public')->putFileAs('files/instituicao', $request->file('logomarca'), $name . '.' . $extension);

            return Storage::disk('public')->url($fileSaved);
        }
        return null;
    }
}
