<?php

namespace Modules\Course\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\Course\Entities\TurmaDisciplina;

class TurmaDisciplinaRepository extends Repository
{
    public function __construct(TurmaDisciplina $entity)
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
        $query = $this->entity->with($with)
            ->join('disciplinas as disc', 'disc.id', '=', 'turma_disciplinas.disciplina_id')
            ->join('turmas as t', 't.id', '=', 'turma_disciplinas.turma_id')
            ->whereNull('t.deleted_at')
            ->orderBy('disc.nome','ASC');
        if ($search)
        {
            foreach($search as $col => $s){
                $query->where($col, 'like', '%'.$s.'%');
            }
        }

        $query->select('turma_disciplinas.*');

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
