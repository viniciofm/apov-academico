<?php

namespace Modules\Company\Http\Repositories;


use App\Http\Repositories\Repository;
use App\Scopes\ActivedScope;
use Illuminate\Support\Facades\Auth;
use Modules\Company\Entities\Empresa;

class EmpresaRepository extends Repository
{
    public function __construct(Empresa $entity)
    {
        $this->entity = $entity;

        public function get(array $params)
    {
        return $this->searchWithPagination(
            $params['with'],
            $params['page'],
            $params['perPage'],
            $params['search'],
            $params['paginate']
        );
    }

        private function searchWithPagination(
        array $with = [],
        $page = 1,
        $perPage = 10,
        $search = null,
        bool $paginate = false,
        array $columns = ['*']
    ) {
        $query = $this->entity
            ->whereHas('usuario', function($q){
                $q->where('instituicao_id', '=', Auth::user()->instituicao_id);
            })
            ->withoutGlobalScope(ActivedScope::class)->with($with)->orderBy('nome', 'ASC')->orderBy('created_at', 'DESC');
        if ($search && isset($search['nome']) && isset($search['cpf_cnpj'])) {
            $query->where('nome', 'like', '%'.$search['nome'].'%')->where('cpf_cnpj', 'like', '%'.$search['cpf_cnpj'].'%');
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
}
