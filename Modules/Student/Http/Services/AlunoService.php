<?php

namespace Modules\Student\Http\Services;

use App\Http\Services\Service;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Student\Http\Repositories\AlunoRepository;
use Modules\User\Http\Services\EnderecoService;
use Modules\User\Http\Services\GeneroService;
use Modules\User\Http\Services\TipoUsuarioService;
use Modules\User\Http\Services\UserService;

class AlunoService extends Service
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
     * @param  AlunoRepository  $repository
     * @param  EnderecoService  $enderecoService
     * @param  UserService  $usuarioService
     * @param  TipoUsuarioService  $tipoUsuarioService
     * @param  GeneroService  $generoService
     */
    public function __construct(AlunoRepository $repository, EnderecoService $enderecoService,
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
            $userAuth = Auth::user();
            $attributes = $request->all();
            //criar endereço
            if(!$attributes['endereco']['cidade_id']){
                $attributes['endereco']['cidade_id'] = NULL;
            }
            $endereco = $this->enderecoService->create($attributes['endereco']);
            //criar usuário
            $dadosUsuario = Arr::only($attributes['usuario'], ['nome', 'genero_id', 'email', 'tipo_documento', 'cpf_cnpj']);
            $dadosUsuario['password'] = \Hash::make(preg_replace('/[^0-9]/', '', $attributes['usuario']['cpf_cnpj']));
            $tipoUsuario = $this->tipoUsuarioService->where('nome', '=', 'aluno');
            $dadosUsuario['tipo_usuario_id'] = count($tipoUsuario) > 0 ? $tipoUsuario[0]->id : null;
            $dadosUsuario['instituicao_id'] = $userAuth->instituicao_id;
            $dadosUsuario['endereco_id'] = $endereco ? $endereco->id : null;
            $usuario = $this->usuarioService->create($dadosUsuario);

            //criar registro
            $attributes['user_id'] = $usuario ? $usuario->id : null;
            $attributes['matricula'] = $this->repository->nextRegistry($userAuth->instituicao_id);
            $registro =  $this->repository->create($attributes);
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
    public function update($id, $request)
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

            if($attributes['data_nascimento']){
                $attributes['data_nascimento'] = Carbon::createFromTimeString($attributes['data_nascimento']);
            }

            $usuario = $this->usuarioService->update($registro->user_id, Arr::only($attributes['usuario'], ['nome', 'email', 'cpf_cnpj', 'tipo_documento', 'genero_id']));
            $endereco = $this->enderecoService->update($usuario->endereco_id, $attributes['endereco']);
            //atualiza registro
            $registro =  $this->repository->update($id ,$attributes);
            DB::commit();

            return $registro;
        } catch(\Exception $e){
            DB::rollBack();
        }
        return null;
    }

    /**
     * @param  array  $params
     * @return mixed
     */
    public function getMatriculas(array $params)
    {
        return $this->repository->getMatriculas($params);
    }
}
