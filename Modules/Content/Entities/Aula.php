<?php

namespace Modules\Content\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'aulas';

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
