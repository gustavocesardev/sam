<?php

namespace App\Domain\Model\GrupoEstudo;

use App\Domain\Model\Abstract\PublicacaoVisualizacaoAbstract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicacaoVisualizacao extends PublicacaoVisualizacaoAbstract
{
    protected $table = 'grupo_estudo_publicacao_visualizacao';
    
    protected $fillable = [
        'id_membro', 
        ...self::FIELDS
    ];

    public function publicacao(): BelongsTo
    {
        return $this->belongsTo(Publicacao::class, 'id_publicacao');
    }

    public function membro(): BelongsTo
    {
        return $this->belongsTo(Membro::class, 'id_membro');
    }
}
