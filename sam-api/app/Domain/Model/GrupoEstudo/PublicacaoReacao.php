<?php

namespace App\Domain\Model\GrupoEstudo;

use App\Domain\Model\Abstract\PublicacaoReacaoAbstract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicacaoReacao extends PublicacaoReacaoAbstract
{
    protected $table = 'grupo_estudo_publicacao_reacao';

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
