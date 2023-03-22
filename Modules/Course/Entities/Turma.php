<?php

namespace Modules\Course\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Turma extends Model
{
    use UsesUuid;
    use SoftDeletes;

    protected $primaryKey = 'id';

    protected $table = 'turmas';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'codigo',
        'grade_id',
        'ativo'
    ];

    protected $casts = [

    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function turmaDisciplinas()
    {
        return $this->hasMany(TurmaDisciplina::class);
    }
}
