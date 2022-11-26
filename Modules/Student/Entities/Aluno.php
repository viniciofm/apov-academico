<?php

namespace Modules\Student\Entities;

use App\Scopes\BlockedScope;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Endereco;
use Modules\User\Entities\User;

class Aluno extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'alunos';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'matricula',
        'telefone',
        'ativo',
        'user_id'
    ];

    protected $casts = [
        'matricula' => 'int',
        'ativo' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScope(BlockedScope::class);
    }

    public function endereco()
    {
        return $this->usuario->belongsTo(Endereco::class);
    }

    protected static function boot()
    {
        parent::boot();

//        static::addGlobalScope(new ActivedScope());
    }

}
