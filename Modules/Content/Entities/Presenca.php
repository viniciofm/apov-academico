<?php

namespace Modules\Content\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Presenca extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'presencas';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'presente',
        'matricula_id',
        'aula_id'
    ];

    protected $casts = [

    ];

}
