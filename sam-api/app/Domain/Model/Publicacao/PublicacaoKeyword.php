<?php

namespace App\Domain\Model\Publicacao;

use App\Domain\Model\Abstract\PublicacaoKeywordAbstract;
use App\Domain\Model\Publicacao\Publicacao;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicacaoKeyword extends PublicacaoKeywordAbstract
{
    protected $table = 'publicacao_keyword';

    public function publicacao(): BelongsTo
    {
        return $this->belongsTo(Publicacao::class, 'id_publicacao');
    }
}
