<?php

namespace Modules\Content\Http\Services;

use App\Http\Services\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Content\Entities\Atividade;
use Modules\Content\Http\Repositories\AtividadeRepository;
use function PHPUnit\Framework\at;

class AtividadeService extends Service
{
    /**
     * @var NotaService
     */
    private $notaService;

    public function __construct(AtividadeRepository $repository, NotaService $notaService)
    {
        $this->repository = $repository;
        $this->notaService = $notaService;
    }

    /**
     * @param $request
     * @return \Exception|mixed
     */
    public function create($request)
    {
        try {
            DB::beginTransaction();

            $attributes = $request->all();
            if($attributes['data']){
                $attributes['data'] = Carbon::createFromTimeString($attributes['data']);
            }

            //checa se vai passar de 100 pontos na disciplina
            if ($this->checkSumPesoAtividades($attributes) > 100){
                throw new \Exception('Nota excede o limite de 100 pontos para a disciplina!');
            }

            $registro =  $this->repository->create($attributes);

            DB::commit();
            return $registro;
        } catch(\Exception $e){
            DB::rollBack();
            return $e;
        }
        return null;
    }

    /**
     * @param $id
     * @param $request
     * @return \Exception|mixed
     */
    public function update($id, $request)
    {
        try {
            DB::beginTransaction();

            $attributes = $request->all();
            if($attributes['data']){
                $attributes['data'] = Carbon::createFromTimeString($attributes['data']);
            }

            //checa se vai passar de 100 pontos na disciplina
            if ($this->checkSumPesoAtividades($attributes['turma_disciplina_id']) > 100){
                throw new \Exception('Nota excede o limite de 100 pontos para a disciplina!');
            }

            $registro = $this->repository->update($id, $attributes);

            DB::commit();
            return $registro;
        } catch(\Exception $e){
            DB::rollBack();
            return $e;
        }
        return null;
    }

    /**
     * @param  array  $attributes
     * @return float
     */
    public function checkSumPesoAtividades(array $attributes) : float
    {
        $sum = 0;

        $atividades = $this->whereFunc(function($q) use ($attributes) {
            $q->where('turma_disciplina_id', '=', $attributes['turma_disciplina_id'])
                ->where('id', '!=', $attributes['id'] ?? null);
        });
        foreach ($atividades as $atividade) {
            $sum += $atividade->peso;
        }
        $sum += $attributes['peso'];
        return $sum;
    }

    /**
     * @param  Atividade  $atividade
     * @return Atividade
     */
    public function getStudentsByActivity(Atividade $atividade) : Atividade
    {
        $atividade = Atividade::with('turmaDisciplina.matriculasTurma.matricula.aluno.usuario')
            ->find($atividade->id)->makeHidden('aluno.telefone','aluno.data_nascimento','usuario.email','usuario.cpf_cnpj');

        foreach ($atividade->turmaDisciplina->matriculasTurma as &$matricula){
            $matricula->nota_atividade = $this->notaService->whereFunc(function ($q) use ($matricula, $atividade){
                    $q->where('matricula_id', $matricula->matricula_id)
                        ->where('atividade_id', $atividade->id);
            })->first() ?? [];
        }

        return $atividade;
    }

    /**
     * @param  Atividade  $atividade
     * @return Atividade
     */
    public function storeNotas(Atividade $atividade, array $notas) : bool
    {
        foreach ($notas as $nota){
            if (isset($nota['id'])){
                //salvar nota
                $this->notaService->update($nota['id'], ['nota' => $nota['nota']]);
            }else{
                //atualizar nota
                $this->notaService->create(['nota' => $nota['nota'], 'matricula_id' => $nota['matricula_id'], 'atividade_id' => $atividade->id]);
            }
        }

        return true;
    }
}
