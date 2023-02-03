<?php

namespace Modules\Student\Http\Repositories;


use App\Http\Repositories\Repository;
use App\Scopes\ActivedScope;
use App\Scopes\BlockedScope;
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
     * @param  bool  $activeAttribute
     * @param  string|null  $sortBy
     * @return mixed
     */
    public function all(bool $activeAttribute = false,string $sortBy = NULL)
    {
        $entity = !$activeAttribute ? $this->entity :  $this->entity::where('ativo', true);
        $entity->withoutGlobalScope(ActivedScope::class)->with('usuario:id,nome');

        return $entity->get()->sortBy('usuario.nome');
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

    public function getMatriculas(array $params){
        $user = Auth::user();

        if($user && $user->tipo_usuario->nome == 'aluno'){
            $aluno = $this->whereWith('user_id', '=', $user->id, []);

            $matriculas = $aluno->matriculas();
            if ($params['search'])
            {
                foreach($params['search'] as $col => $s){
                    if($col == 'status'){
                        $matriculas->where('status', 'like', '%'.$s.'%');
                    }
                }
            }

            if ($params['paginate']) {
                return $matriculas->paginate(
                    $params['perPage'],
                    ['*'],
                    'page',
                    $params['page']
                );
            }

            return $aluno->matriculas;
        }

        return array();
    }
}
