<?php

namespace Modules\User\Entities;

use App\Scopes\BlockedScope;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Instituition\Entities\Instituicao;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    use UsesUuid;

    protected $primaryKey = 'id';

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'tipo_usuario_id',
        'instituicao_id',
        'nome',
        'cpf_cnpj',
        'tipo_documento',
        'email',
        'endereco_id',
        'genero_id',
        'password',
        'blocked',
        'consulta',
        'email_verified_at',
        'remember_token'
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'blocked' => false
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'blocker' => 'boolean',
    ];

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class);
    }

    public function genero()
    {
        return $this->belongsTo(Genero::class);
    }

    public function tipo_usuario()
    {
        return $this->belongsTo(TipoUsuario::class);
    }

    public function endereco()
    {
        return $this->belongsTo(Endereco::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BlockedScope());
    }
}
