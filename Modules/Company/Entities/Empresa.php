<?php

namespace Modules\Company\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'empresas';

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
