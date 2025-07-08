<?php

namespace App\Domain\Model\Publicacao;

use App\Domain\Model\Abstract\PublicacaoAbstract;

use App\Domain\Model\Publicacao\PublicacaoReacao;
use App\Domain\Model\User;

use Database\Factories\PublicacaoFactory;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publicacao extends PublicacaoAbstract
{
    use HasFactory;
    
    protected $table = 'publicacao';
    
    protected $fillable = [
        'id_usuario',
        ...self::FIELDS
    ];

    public function getBasePath(): string
    {
        return "instituicoes/{$this->user->curso->id_instituicao}/cursos/{$this->user->curso->id}/users/{$this->user->id}/publicacoes/{$this->id}";
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

    public function visualizacoes(): HasMany
    {
        return $this->hasMany(PublicacaoVisualizacao::class, 'id_publicacao');
    }

    protected static function newFactory(): PublicacaoFactory
    {
        return PublicacaoFactory::new();
    }
}