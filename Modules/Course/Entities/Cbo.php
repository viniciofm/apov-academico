<?php

namespace Modules\Course\Entities;

use App\Scopes\ActivedScope;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Course\Http\Services\TurmaDisciplinaService;

class Cbo extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'cbos';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'codigo',
        'nome',
        'ativo'
    ];

    protected $casts = [

    ];

    /**
     * @return HasMany
     */
    public function turmaDisciplinas(): HasMany
    {
        return $this->hasMany(TurmaDisciplina::class);
    }

    /**
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ActivedScope());
    }
}
