<?php

namespace Modules\Course\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Content\Entities\Atividade;
use Modules\Content\Entities\Aula;
use Modules\Student\Entities\TurmaDisciplinaMatricula;
use Modules\Teacher\Entities\Professor;

class TurmaDisciplina extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'turma_disciplinas';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'turma_id',
        'professor_id',
        'disciplina_id'
    ];

    protected $casts = [

    ];

    /**
     * @return BelongsTo
     */
    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class);
    }

    /**
     * @return BelongsTo
     */
    public function turma(): BelongsTo
    {
        return $this->belongsTo(Turma::class);
    }

    /**
     * @return BelongsTo
     */
    public function professor(): BelongsTo
    {
        return $this->belongsTo(Professor::class);
    }

    /**
     * @return HasMany
     */
    public function matriculasTurma(): HasMany
    {
        return $this->hasMany(TurmaDisciplinaMatricula::class);
    }

    /**
     * @return HasMany
     */
    public function aulas(): HasMany
    {
        return $this->hasMany(Aula::class);
    }
}
