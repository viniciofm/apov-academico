<?php

namespace Modules\Course\Http\Repositories;


use App\Http\Repositories\Repository;
use App\Scopes\ActivedScope;
use Illuminate\Support\Facades\Auth;
use Modules\Course\Entities\Turma;

class TurmaRepository extends Repository
{
    public function __construct(Turma $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @param  string  $codigo
     * @param  string|null  $idUpdate
     * @return bool
     */
    public function canRegisterCadastro(string $codigo, string $idUpdate = NULL) : bool
    {
        $recorrente = $this->where('codigo', '=', $codigo)->first();
        if ($recorrente && $recorrente->id != $idUpdate) {
            return false;
        }

        return true;
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
            ->whereHas('grade', function($q) use ($search){
                $q->where('codigo', 'like', '%'.$search['codigo_grade'].'%')->whereHas('curso', function($qq) use ($search){
                    $qq->where('nome', 'like', '%'.$search['nome_curso'].'%');
                });
            })
            ->withoutGlobalScope(ActivedScope::class)->with($with)
            ->orderBy('created_at','DESC');

        if ($search && isset($search['codigo'])) {
            $query->where('codigo', 'like', '%'.$search['codigo'].'%');
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
