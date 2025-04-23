<?php

namespace App\Domain\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicacaoKeyword extends Model
{
    protected $table = 'publicacao_keyword';

    protected $fillable = [
        'id_publicacao',
        'keyword',
        'frequencia'
    ];

    public function publicacao(): BelongsTo
    {
        return $this->belongsTo(Publicacao::class, 'id_publicacao');
    }
}
