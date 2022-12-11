<?php

namespace Modules\Content\Http\Services;

use App\Http\Services\Service;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Content\Http\Repositories\AulaRepository;

class AulaService extends Service
{
    public function __construct(AulaRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $request
     * @return int|null
     */
    public function create($request)
    {
        try {
            DB::beginTransaction();

            $count = 0;
            $attributes = $request->all();
            if($attributes['data']){
                $attributes['data'] = Carbon::createFromTimeString($attributes['data']);
            }
            $numero_aulas = (int) $attributes['numero_aulas'];
            for ($i = 0; $i < $numero_aulas; $i++){
                $registro =  $this->repository->create($attributes);
                if ($registro) $count++;
            }

            DB::commit();
            return $count;
        } catch(\Exception $e){
            DB::rollBack();
        }
        return null;
    }
}
