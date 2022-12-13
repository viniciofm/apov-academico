<?php

namespace Modules\Content\Http\Services;

use App\Http\Services\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Content\Http\Repositories\AtividadeRepository;
use function PHPUnit\Framework\at;

class AtividadeService extends Service
{
    public function __construct(AtividadeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $request
     * @return mixed|null
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
     * @param $request
     * @return mixed|null
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
}
