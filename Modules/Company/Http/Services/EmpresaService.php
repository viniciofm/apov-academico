<?php

namespace Modules\Company\Http\Services;

use App\Http\Services\Service;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Company\Http\Repositories\EmpresaRepository;
use Modules\User\Http\Services\EnderecoService;
use Modules\User\Http\Services\GeneroService;
use Modules\User\Http\Services\TipoUsuarioService;
use Modules\User\Http\Services\UserService;

class EmpresaService extends Service
{
    /**
     * @var EnderecoService $enderecoService
     */
    protected $enderecoService;

    /**
     * @var UserService
     */
    protected $usuarioService;

    /**
     * @var TipoUsuarioService
     */
    protected $tipoUsuarioService;

    /**
     * @var GeneroService
     */
    protected $generoService;

    /**
     * @param  EmpresaRepository  $repository
     * @param  EnderecoService  $enderecoService
     */
    public function __construct(EmpresaRepository $repository, EnderecoService $enderecoService,
        UserService $usuarioService, TipoUsuarioService $tipoUsuarioService, GeneroService $generoService)
    {
        $this->repository = $repository;
        $this->enderecoService = $enderecoService;
        $this->usuarioService = $usuarioService;
        $this->tipoUsuarioService = $tipoUsuarioService;
        $this->generoService = $generoService;
    }

    /**
     * @param  array  $attributes
     * @return mixed|null
     */
    public function create($request)
    {
        try {
            DB::beginTransaction();

            $attributes = $request->all();
            //criar endereço
            if(!$attributes['endereco']['cidade_id']){
                $attributes['endereco']['cidade_id'] = NULL;
            }
            $endereco = $this->enderecoService->create($attributes['endereco']);
            //criar usuário
            $dadosUsuario = Arr::only($attributes, ['email', 'tipo_documento', 'cpf_cnpj']);
            $dadosUsuario['password'] = \Hash::make((int) filter_var($attributes['cpf_cnpj'],
                FILTER_SANITIZE_NUMBER_INT));
            $tipoUsuario = $this->tipoUsuarioService->where('nome', '=', 'empresa');
            $genero = $this->generoService->where('nome', 'like', '%Não%');
            $dadosUsuario['tipo_usuario_id'] = count($tipoUsuario) > 0 ? $tipoUsuario[0]->id : null;
            $dadosUsuario['genero_id'] = count($genero) > 0 ? $genero[0]->id : null;
            $dadosUsuario['instituicao_id'] = Auth::user()->instituicao_id;
            $dadosUsuario['name'] = $attributes['nome'];
            $usuario = $this->usuarioService->create($dadosUsuario);

            //save image
            if ($request->hasFile('logomarca')) {
                $attributes['logomarca'] = $this->saveLogoMarca($request);
            }

            //criar empresa
            $attributes['endereco_id'] = $endereco ? $endereco->id : null;
            $attributes['user_id'] = $usuario ? $usuario->id : null;
            $empresa =  $this->repository->create($attributes);
            DB::commit();

            return $empresa;
        } catch(\Exception $e){
            DB::rollBack();
        }
        return null;
    }

    /**
     * @param $id
     * @param $request
     * @return mixed|null
     */
    public function update($id, $request)
    {
        try {
            DB::beginTransaction();

            $attributes = $request->all();
            $empresa = $this->repository->find($id);
            //atualiza endereço
            if(!$attributes['endereco']['cidade_id']){
                $attributes['endereco']['cidade_id'] = NULL;
            }
            if(!$attributes['endereco']['numero']){
                $attributes['endereco']['numero'] = NULL;
            }

            //save image
            if ($request->hasFile('logomarca')) {
                $attributes['logomarca'] = $this->saveLogoMarca($request, $empresa);
            }

            $endereco = $this->enderecoService->update($empresa->endereco_id, $attributes['endereco']);
            //atualiza empresa
            $empresa =  $this->repository->update($id ,$attributes);
            DB::commit();

            return $empresa;
        } catch(\Exception $e){
            DB::rollBack();
        }
        return null;
    }

    public function saveLogoMarca($request, $empresa = NULL){
        //save image
        if ($request->hasFile('logomarca')) {
            $name = sprintf('%d%d%s', date('Y'), date('n') < 6 ? 1 : 2, bin2hex(random_bytes(6)));
            $extension = pathinfo($request->file('logomarca')->getClientOriginalName(), PATHINFO_EXTENSION);
            if ($empresa && $empresa->logomarca) {
                Storage::disk('public')->delete(explode(env('APP_URL').Storage::url(''),$empresa->logomarca)[1]);
            }

            $fileSaved = Storage::disk('public')->putFileAs('files/empresa', $request->file('logomarca'), $name . '.' . $extension);

            return Storage::disk('public')->url($fileSaved);
        }
        return null;
    }
}
