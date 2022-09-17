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
        'name',
        'cpf_cnpj',
        'tipo_documento',
        'email',
        'endereco_id',
        'password',
        'blocked',
        'email_verified_at',
        'remember_token'
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

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BlockedScope());
    }
}
