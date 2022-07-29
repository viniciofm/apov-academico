<?php

namespace Modules\User\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'cidades';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'nome',
        'estado_id'
    ];

    protected $casts = [

    ];

}
