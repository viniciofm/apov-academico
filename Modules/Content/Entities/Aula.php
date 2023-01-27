<?php

namespace Modules\Content\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Course\Entities\TurmaDisciplina;

class Aula extends Model
{
    use UsesUuid;
    use SoftDeletes;

    protected $primaryKey = 'id';

    protected $table = 'aulas';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'data',
        'conteudo',
        'turma_disciplina_id'
    ];

    protected $casts = [
        'data' => 'date',
    ];


    /**
     * @return BelongsTo
     */
    public function turmaDisciplina(): BelongsTo
    {
        return $this->belongsTo(TurmaDisciplina::class);
    }

}
