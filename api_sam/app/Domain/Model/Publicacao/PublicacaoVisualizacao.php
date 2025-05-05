<?php

namespace App\Domain\Model\Publicacao;

use App\Domain\Model\Abstract\AbstractPublicacaoVisualizacao;
use App\Domain\Model\User;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicacaoVisualizacao extends AbstractPublicacaoVisualizacao
{
    protected $table = 'publicacao_visualizacao';
    
    protected $fillable = [
        'id_usuario', 
        ...self::FIELDS
    ];

    public function publicacao(): BelongsTo
    {
        return $this->belongsTo(Publicacao::class, 'id_publicacao');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
