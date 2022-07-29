<?php

namespace Modules\Content\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Atividade extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'atividades';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'titulo',
        'descricao',
        'data',
        'turma_disciplina_id'
    ];

    protected $casts = [

    ];

}
