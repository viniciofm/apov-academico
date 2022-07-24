<?php

namespace Modules\Instituition\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Instituicao extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'instituicoes';

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
