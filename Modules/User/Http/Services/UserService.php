<?php

namespace Modules\User\Http\Services;

use App\Http\Services\Service;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\User\Http\Repositories\UserRepository;

class UserService extends Service
{

    /**
     * @var EnderecoService $enderecoService
     */
    protected $enderecoService;

    /**
     * @var TipoUsuarioService
     */
    protected $tipoUsuarioService;

    /**
     * @param  UserRepository  $repository
     * @param  EnderecoService  $enderecoService
     */
    public function __construct(UserRepository $repository, EnderecoService $enderecoService, TipoUsuarioService $tipoUsuarioService)
    {
        $this->repository = $repository;
        $this->enderecoService = $enderecoService;
        $this->tipoUsuarioService = $tipoUsuarioService;
    }

    /**
     * @param  array  $request
     * @param  string  $nomeTipoUsuario
     * @return bool
     */
    public function canRegisterCadastro(array $request, string $nomeTipoUsuario, string $userIdUpdate = NULL) : bool
    {
        return $this->repository->canRegisterCadastro($request, $nomeTipoUsuario, $userIdUpdate);
    }

    /**
     * @param  array  $attributes
     * @return mixed|null
     */
    public function createUserAdmin($request)
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
            $dadosUsuario = Arr::only($attributes, ['nome', 'genero_id', 'email', 'tipo_documento', 'cpf_cnpj']);
            $dadosUsuario['password'] = \Hash::make($attributes['password']);
            $tipoUsuario = $this->tipoUsuarioService->where('nome', '=', 'admin');
            $dadosUsuario['tipo_usuario_id'] = count($tipoUsuario) > 0 ? $tipoUsuario[0]->id : null;
            $dadosUsuario['endereco_id'] = $endereco ? $endereco->id : null;
            $registro = $this->create($dadosUsuario);
            DB::commit();

            return $registro;
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
    public function updateUserAdmin($id, $request)
    {
        try {
            DB::beginTransaction();

            $attributes = $request->all();
            $registro = $this->repository->find($id);
            //atualiza endereço
            if(!$attributes['endereco']['cidade_id']){
                $attributes['endereco']['cidade_id'] = NULL;
            }
            if(!$attributes['endereco']['numero']){
                $attributes['endereco']['numero'] = NULL;
            }

            if(!empty($attributes['password'])){
                $attributes['password'] = \Hash::make($attributes['password']);
            }
            if($registro->endereco_id){
                $endereco = $this->enderecoService->update($registro->endereco_id, $attributes['endereco']);
            }else{
                $endereco = $this->enderecoService->create($attributes['endereco']);
                $attributes['endereco_id'] = $endereco ? $endereco->id : null;
            }
            $usuario = $this->repository->update($id, $attributes);

            DB::commit();

            return $registro;
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
    public function updatePassword($request)
    {
        try {
            DB::beginTransaction();

            $attributes = [];
            if(!empty($attributes['password'])){
                $attributes['password'] = \Hash::make($request['password']);
            }
            $usuario = $this->repository->update($request['id'], $attributes);

            DB::commit();

            return $usuario;
        } catch(\Exception $e){
            DB::rollBack();
        }
        return null;
    }
}
