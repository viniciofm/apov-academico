<?php

namespace Modules\Student\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class TurmaDisciplinaMatricula extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'turma_disciplina_matricula';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'turma_disciplina_id',
        'matricula_id',
        'nota_final',
        'status'
    ];

    protected $casts = [

    ];

}
