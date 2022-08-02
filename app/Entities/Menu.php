<?php

namespace App\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'menus';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'position',
        'nome',
        'url',
        'icon',
        'tipo_usuario_id'
    ];

    protected $casts = [

    ];
}
