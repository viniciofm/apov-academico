<?php

namespace Modules\User\Http\Repositories;


use App\Http\Repositories\Repository;
use App\Scopes\BlockedScope;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\User;
use Modules\User\Http\Services\TipoUsuarioService;

class UserRepository extends Repository
{
    /**
     * @var TipoUsuarioService
     */
    protected $tipoUsuarioService;

    public function __construct(User $entity, TipoUsuarioService $tipoUsuarioService)
    {
        $this->entity = $entity;
        $this->tipoUsuarioService = $tipoUsuarioService;
    }

    /**
     * @param  array  $request
     * @param  string  $nomeTipoUsuario
     * @return bool
     */
    public function canRegisterCadastro(array $request, string $nomeTipoUsuario, string $userIdUpdate = NULL) : bool
    {
        $tipoUsuario = $this->tipoUsuarioService->where('nome', '=', $nomeTipoUsuario);
        if($tipoUsuario){
            $usuario = $this->entity
                ->where(function($query) use ($request) {
                    $query->where('instituicao_id', Auth::user()->instituicao_id);
                })
                ->where(function($query) use ($request) {
                    $query->where('email', $request['email'])->orWhere('cpf_cnpj', $request['cpf_cnpj']);
                });

            $usuario->where('tipo_usuario_id', $tipoUsuario->first()->id);

            if ($userIdUpdate){
                $usuario = $usuario->where('id', '!=', $userIdUpdate);
            }
            $usuario = $usuario->first();

            if($usuario){
                return false;
            }
        }

        return true;
    }

    public function get(array $params)
    {
        return $this->searchWithPagination(
            $params['with'],
            $params['page'],
            $params['perPage'],
            $params['column'] ?? null,
            $params['search'],
            $params['paginate']
        );
    }

    private function searchWithPagination(
        array $with = [],
        $page = 1,
        $perPage = 10,
        $column = null,
        $search = [],
        bool $paginate = false,
        array $columns = ['*']
    )
    {
        $query = $this->entity->withoutGlobalScope(BlockedScope::class)->with($with)->orderBy('blocked')->orderBy('nome');
        if ($search)
        {
            foreach($search as $col => $s){
                if($col != 'tipo_usuario'){
                    $query->where($col, 'like', '%'.$s.'%');
                }else{
                    $query->whereHas('tipo_usuario', function($q) use ($s) {
                        $q->where('nome', 'like', '%'.$s.'%');
                    });
                }
            }
        }

        if ($paginate) {
            return $query->paginate(
                $perPage,
                ['*'],
                'page',
                $page
            );
        }

        return $query->get($columns);
    }
}
