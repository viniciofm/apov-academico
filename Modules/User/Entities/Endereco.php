<?php

namespace Modules\User\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'enderecos';

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
