<?php

namespace Modules\Course\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Modules\Course\Http\Services\TurmaDisciplinaService;

class Disciplina extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'disciplinas';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'sigla',
        'nome',
        'carga_horaria',
        'grade_id'
    ];

    protected $casts = [

    ];

    public function turmaDisciplinas()
    {
        return $this->hasMany(TurmaDisciplina::class);
    }
}
