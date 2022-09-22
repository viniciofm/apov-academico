<?php

namespace Modules\Company\Entities;

use App\Scopes\ActivedScope;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;

class Empresa extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'empresas';

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
        'user_id',
        'ativo',
        'endereco_id'
    ];

    protected $casts = [

    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ActivedScope());
    }

}
