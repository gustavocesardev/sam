<?php

namespace App\Domain\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publicacao extends Model
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

    protected static function boot()
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

    public function excluir(): bool
    {
        $this->excluido = true;
        $this->excluido_data = Carbon::now();

        return $this->save();
    }

    public function updateImagens(array $newPaths): void
    {
        $this->imagens = $newPaths;
    }
}