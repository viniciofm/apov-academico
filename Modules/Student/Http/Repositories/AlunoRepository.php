<?php

namespace Modules\Student\Http\Repositories;


use App\Http\Repositories\Repository;
use App\Scopes\ActivedScope;
use Illuminate\Support\Facades\Auth;
use Modules\Student\Entities\Aluno;

class AlunoRepository extends Repository
{
    public function __construct(Aluno $entity)
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
            ->whereHas('usuario', function($q) use ($search){
                $q->where('instituicao_id', '=', Auth::user()->instituicao_id);
                if ($search && isset($search['nome']) && isset($search['cpf_cnpj'])) {
                    $q->where('nome', 'like', '%'.$search['nome'].'%')->where('cpf_cnpj', 'like', '%'.$search['cpf_cnpj'].'%');
                }
            })
            ->withoutGlobalScope(ActivedScope::class)->with($with)
            ->join('users as usuario', 'usuario.id', '=', 'alunos.user_id')
            ->select('usuario.*', 'alunos.*')
            ->orderBy('usuario.nome','ASC');

        if ($search && isset($search['matricula'])) {
            $query->where('matricula', 'like', '%'.$search['matricula'].'%');
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

    /**
     * @param  string  $instituicao_id
     * @return void
     */
    public function nextRegistry(string $instituicao_id)
    {
        $lastRegistry = $this->entity::whereHas('usuario', function($q) use ($instituicao_id){
            $q->where('instituicao_id', '=', $instituicao_id);
        })->orderByDesc('created_at')->withoutGlobalScope(ActivedScope::class)->first();
        return $lastRegistry ? $lastRegistry->matricula + 1 : 1;
    }
}
