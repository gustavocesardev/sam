<?php

namespace App\Domain\Model\GrupoEstudo;

use App\Domain\Model\Abstract\PublicacaoAbstract;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publicacao extends PublicacaoAbstract
{
    protected $table = 'grupo_estudo_publicacao';
    
    protected $fillable = [
        'id_membro',
        ...self::FIELDS
    ];

    public function getBasePath(): string
    {
        return "instituicoes/{$this->membro->user->curso->id_instituicao}/cursos/{$this->membro->user->curso->id}/grupos_estudos/{$this->membro->grupoEstudo->id}/membros/{$this->membro->id}/publicacoes/{$this->id}";
    }

    public function getIdUsuario(): int
    {
        return $this->id_membro;
    }

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope('nao_excluido', function (Builder $builder) {
            $builder->where('excluido', false);
        });
    }

    public function membro(): BelongsTo
    {
        return $this->belongsTo(Membro::class, 'id_membro');
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
