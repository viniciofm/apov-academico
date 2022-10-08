<?php

namespace Modules\Student\Http\Repositories;


use App\Http\Repositories\Repository;
use App\Scopes\ActivedScope;
use Illuminate\Support\Facades\Auth;
use Modules\Student\Entities\Matricula;

class MatriculaRepository extends Repository
{
    public function __construct(Matricula $entity)
    {
        $this->entity = $entity;
    }

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
            ->whereHas('aluno', function($q) use ($search){
                $q->where('matricula', 'like', '%'.$search['matricula'].'%');
                $q->whereHas('usuario', function($qq) use ($search){
                    $qq->where('instituicao_id', '=', Auth::user()->instituicao_id);
                    $qq->where('nome', 'like', '%'.$search['nome_aluno'].'%');
                });
            })->whereHas('curso', function($q) use ($search){
                $q->where('nome', 'like', '%'.$search['nome_curso'].'%');
            })
            ->withoutGlobalScope(ActivedScope::class)->with($with);

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
