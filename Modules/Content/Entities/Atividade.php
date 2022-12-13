<?php

namespace Modules\Content\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Atividade extends Model
{
    use UsesUuid;
    use SoftDeletes;

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
        'peso',
        'data',
        'turma_disciplina_id'
    ];

    protected $casts = [

    ];

}
