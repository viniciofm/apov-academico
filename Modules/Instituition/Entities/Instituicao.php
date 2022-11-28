<?php

namespace Modules\Instituition\Entities;

use App\Scopes\ActivedScope;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Endereco;

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
        'responsavel_cargo',
        'telefone_contato',
        'cpf_cnpj',
        'tipo_documento',
        'logomarca',
        'endereco_id'
    ];

    protected $casts = [

    ];

    public function endereco()
    {
        return $this->belongsTo(Endereco::class);
    }
}
