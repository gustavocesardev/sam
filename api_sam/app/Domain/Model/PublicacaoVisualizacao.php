<?php

namespace App\Domain\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicacaoVisualizacao extends Model
{
    protected $table = 'publicacao_visualizacao';
    
    protected $fillable = [
        'id_publicacao',
        'id_usuario',
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
