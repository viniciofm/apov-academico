<?php

namespace Modules\User\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'tipo_usuarios';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'nome'
    ];

    protected $casts = [

    ];

}
