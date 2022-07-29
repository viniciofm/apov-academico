<?php

namespace Modules\Instituition\Entities;

use App\Scopes\ActivedScope;
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
        'id',
        'nome',
        'email',
        'responsavel',
        'telefone_contato',
        'cpf_cnpj',
        'tipo_documento',
        'logomarca',
        'endereco_id'
    ];

    protected $casts = [

    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ActivedScope());
    }
}
