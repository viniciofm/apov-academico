<?php

namespace Modules\Student\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'alunos';

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
