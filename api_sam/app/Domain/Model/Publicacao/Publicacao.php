<?php

namespace App\Domain\Model\Publicacao;

use App\Domain\Model\Abstract\AbstractPublicacao;

use App\Domain\Model\Publicacao\PublicacaoReacao;
use App\Domain\Model\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publicacao extends AbstractPublicacao
{
    protected $table = 'publicacao';
    
    protected $fillable = [
        'id_usuario',
        'id_publicacao_vinculada',
        'texto',
        'imagens',
        'qtde_curtidas',
        'qtde_visualizacoes',
        'excluido',
        'excluido_data'
    ];

    protected $casts = [
        'imagens' => 'array',
        'excluido' => 'boolean',
        'excluido_data' => 'date',
    ];

    public function getBaseImagePath(User $user): string
    {
        return "instituicoes/{$user->curso->id_instituicao}/cursos/{$user->curso->id}/users/{$user->id}/publicacoes/{$this->id}";
    }

    public function getIdUsuario(): int
    {
        return $this->id_usuario;
    }

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope('nao_excluido', function (Builder $builder) {
            $builder->where('excluido', false);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function publicacaoVinculada(): BelongsTo
    {
        return $this->belongsTo(Publicacao::class, 'id_publicacao_vinculada');
    }

    public function keywords(): HasMany
    {
        return $this->hasMany(PublicacaoKeyword::class, 'id_publicacao');
    }

    public function reacoes(): HasMany
    {
        return $this->hasMany(PublicacaoReacao::class, 'id_publicacao');
    }
}