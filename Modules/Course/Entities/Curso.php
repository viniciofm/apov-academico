<?php

namespace Modules\Course\Entities;

use App\Scopes\ActivedScope;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Instituition\Entities\Instituicao;

class Curso extends Model
{
    use UsesUuid;
    use SoftDeletes;

    protected $primaryKey = 'id';

    protected $table = 'cursos';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'sigla',
        'nome',
        'ativo',
        'instituicao_id',
        'cbo_id',
        'cnap',
    ];

    protected $casts = [

    ];

    /**
     * @return BelongsTo
     */
    public function cbo(): BelongsTo
    {
        return $this->belongsTo(Cbo::class);
    }

    /**
     * @return BelongsTo
     */
    public function instituicao(): BelongsTo
    {
        return $this->belongsTo(Instituicao::class);
    }

    protected static function boot()
    {
        parent::boot();

//        static::addGlobalScope(new ActivedScope());
    }
}
