<?php

namespace Modules\Student\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'matriculas';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'curso_id',
        'turma_id',
        'empresa_id',
        'aluno_id',
        'nota_final',
        'status',
        'conceito'
    ];

    protected $casts = [

    ];

}
