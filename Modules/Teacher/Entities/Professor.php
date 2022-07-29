<?php

namespace Modules\Teacher\Entities;

use App\Scopes\ActivedScope;
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
        'id',
        'matricula',
        'ativo',
        'user_id'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ActivedScope());
    }
}
