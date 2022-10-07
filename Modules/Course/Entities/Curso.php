<?php

namespace Modules\Course\Entities;

use App\Scopes\ActivedScope;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use UsesUuid;

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
        'instituicao_id'
    ];

    protected $casts = [

    ];

    protected static function boot()
    {
        parent::boot();

//        static::addGlobalScope(new ActivedScope());
    }
}
