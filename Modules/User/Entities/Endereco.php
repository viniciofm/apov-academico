<?php

namespace Modules\User\Entities;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'enderecos';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'rua',
        'numero',
        'bairro',
        'complemento',
        'cep',
        'cidade_id'
    ];

    protected $appends = [
        'estado_id'
    ];

    public function getEstadoIdAttribute(){
        return $this->cidade ? $this->cidade->estado_id : NULL;
    }

    public function cidade()
    {
        return $this->belongsTo(Cidade::class);
    }

    protected $casts = [
        'numero' => 'int',
    ];

}
