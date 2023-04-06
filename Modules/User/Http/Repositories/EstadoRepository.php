<?php

namespace Modules\User\Http\Repositories;


use App\Http\Repositories\Repository;
use Modules\User\Entities\Estado;

class EstadoRepository extends Repository
{
    public function __construct(Estado $entity)
    {
        $this->entity = $entity;
    }

    public function allWithCities()
    {
        return $this->entity->orderBy('nome')->with(['cidades' => function ($query){
            $query->orderBy('nome')->select('id', 'nome', 'estado_id');
        }])->select('id', 'nome')->get()->keyBy('id');
    }
}
