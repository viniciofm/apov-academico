<?php

namespace Modules\Course\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

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

    ];

    protected $casts = [

    ];

}
