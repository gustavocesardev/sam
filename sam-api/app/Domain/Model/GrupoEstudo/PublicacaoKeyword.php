<?php

namespace App\Domain\Model\GrupoEstudo;

use App\Domain\Model\Abstract\PublicacaoKeywordAbstract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicacaoKeyword extends PublicacaoKeywordAbstract
{
    protected $table = 'grupo_estudo_publicacao_keyword';

    public function publicacao(): BelongsTo
    {
        return $this->belongsTo(Publicacao::class, 'id_publicacao');
    }
}
