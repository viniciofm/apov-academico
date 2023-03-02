<?php

namespace Modules\Student\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Modules\Company\Entities\Empresa;
use Modules\Course\Entities\Curso;
use Modules\Course\Entities\Turma;

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
        'status',
        'conceito',
        'motivo_cancelamento',
        'data_saida'
    ];

    protected $casts = [
        'data_saida' => 'date',
    ];

    public function curso(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function turma(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Turma::class);
    }

    public function empresa(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function aluno(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function disciplinasMatricula(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TurmaDisciplinaMatricula::class);
    }
}
