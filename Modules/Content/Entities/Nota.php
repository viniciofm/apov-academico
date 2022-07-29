<?php

namespace Modules\Content\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'notas';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'nota',
        'matricula_id',
        'atividade_id'
    ];

    protected $casts = [

    ];

}
