<?php

namespace Modules\Student\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Content\Entities\Presenca;
use Modules\Course\Entities\TurmaDisciplina;

class TurmaDisciplinaMatricula extends Model
{
    use UsesUuid;
    use SoftDeletes;

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

    public function turmaDisciplina(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TurmaDisciplina::class);
    }

    public function matricula(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }
}
