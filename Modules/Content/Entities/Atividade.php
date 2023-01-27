<?php

namespace Modules\Content\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Course\Entities\TurmaDisciplina;
use Modules\Course\Http\Services\TurmaDisciplinaService;

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

    /**
     * @return BelongsTo
     */
    public function turmaDisciplina(): BelongsTo
    {
        return $this->belongsTo(TurmaDisciplina::class);
    }

    /**
     * @return HasMany
     */
    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class);
    }
}
