<?php

namespace Modules\Course\Entities;

use App\Scopes\ActivedScope;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Entities\Endereco;

class Grade extends Model
{
    use UsesUuid;
    use SoftDeletes;

    protected $primaryKey = 'id';

    protected $table = 'grades';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'ano',
        'periodo',
        'codigo',
        'ativo',
        'curso_id'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function disciplinas()
    {
        return $this->hasMany(Disciplina::class);
    }

    protected static function boot()
    {
        parent::boot();

//        static::addGlobalScope(new ActivedScope());
    }
}
