<?php

namespace Modules\User\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Genero extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'generos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'nome',
    ];

}
