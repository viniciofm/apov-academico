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
    }

    public function get(array $params)
    {
        return $this->searchWithPagination(
            $params['with'],
            $params['page'],
            $params['perPage'],
            $params['column'],
            $params['search'],
            $params['paginate']
        );
    }

    private function searchWithPagination(
        array $with = [],
        $page = 1,
        $perPage = 10,
        $column = null,
        $search = '',
        bool $paginate = false,
        array $columns = ['*']
    ) {
        $query = $this->entity
            ->whereHas('usuario', function($q){
                $q->where('instituicao_id', '=', Auth::user()->instituicao_id);
            })
            ->withoutGlobalScope(ActivedScope::class)->with($with)->orderBy('created_at', 'DESC');
        if ($search && $column) {
            $query->where($column, 'ilike', '%'.$search.'%');
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
