<?php

namespace Modules\Teacher\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'professores';

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
