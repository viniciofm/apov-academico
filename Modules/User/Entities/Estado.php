<?php

namespace Modules\User\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'estados';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'nome',
        'sigla'
    ];

    protected $casts = [

    ];

    public function cidades()
    {
        return $this->hasMany(Cidade::class);
    }
}
