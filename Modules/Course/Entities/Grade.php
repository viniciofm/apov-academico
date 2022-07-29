<?php

namespace Modules\Course\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'grades';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'ano',
        'periodo',
        'codigo',
        'curso_id'
    ];

    protected $casts = [

    ];

}
