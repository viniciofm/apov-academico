<?php

namespace Modules\Content\Http\Services;

use App\Http\Services\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Content\Entities\Atividade;
use Modules\Content\Entities\Nota;
use Modules\Course\Entities\TurmaDisciplina;
use Modules\Content\Http\Repositories\AtividadeRepository;
use Modules\Student\Entities\TurmaDisciplinaMatricula;

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
            if ($this->checkSumPesoAtividades($attributes) > 100){
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
    public function getStudentsByActivity(Atividade $atividade) : array
    {
        $atividade = Atividade::with('turmaDisciplina.matriculasTurma.matricula.aluno.usuario')
            ->find($atividade->id)->makeHidden('aluno.telefone','aluno.data_nascimento','usuario.email','usuario.cpf_cnpj');

        $matriculas = $atividade->turmaDisciplina->matriculasTurma->where('matricula.status', '!=', 'cancelado');
        foreach ($matriculas as &$matricula){
            $matricula->nota_atividade = $this->notaService->whereFunc(function ($q) use ($matricula, $atividade){
                    $q->where('matricula_id', $matricula->matricula_id)
                        ->where('atividade_id', $atividade->id);
            })->first() ?? [];
        }

        $atividade = $atividade->toArray();
        unset($atividade['turma_disciplina']['matriculas_turma']);

        $atividade['turma_disciplina']['matriculas_ativas_turma'] = array_values($matriculas->toArray());

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

        //atualizar nota final dos alunos na turma
        $this->updateNotasAlunos($atividade->turmaDisciplina);
        //atualizar situação dos alunos na turma pela nota
        $this->updateStatusAlunos($atividade->turmaDisciplina);

        return true;
    }

    /**
     * @param  TurmaDisciplina  $turmaDisciplina
     * @return void
     */
    public function updateNotasAlunos(TurmaDisciplina $turmaDisciplina)
    {
        //calcular nota dos alunos da turma
        $notas = Nota::whereHas('atividade', function($q) use ($turmaDisciplina){
            $q->where('turma_disciplina_id', $turmaDisciplina->id);
        })->get();

        $matriculasNotas = [];
        foreach($notas as $nota){
            if (!isset($matriculasNotas[$nota->matricula_id])){
                $matriculasNotas[$nota->matricula_id] = 0.0;
            }

            $matriculasNotas[$nota->matricula_id] += $nota->nota;
        }

        foreach($turmaDisciplina->matriculasTurma as $matricula){
            if (isset($matriculasNotas[$matricula->matricula_id])){
                $matricula->nota_final = round($matriculasNotas[$matricula->matricula_id], 0);
                $matricula->save();
            }
        }

        //calcular alunos da turma pela frequência
    }

    /**
     * @param  TurmaDisciplina  $turmaDisciplina
     * @return void
     */
    public function updateStatusAlunos(TurmaDisciplina $turmaDisciplina)
    {
        //calcular alunos da turma pela nota
        foreach($turmaDisciplina->matriculasTurma as $matricula){
            if($matricula->nota_final >= 60){
                $matricula->status = 'aprovado';
            }else{
                if ($matricula->status == 'aprovado'){
                    $matricula->status = 'reprovado';
                }
            }
            $matricula->save();
        }

        //calcular alunos da turma pela frequência
    }
}
