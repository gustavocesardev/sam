<?php

namespace App\Infrastructure\Persistence\Repository\GrupoEstudo;

use App\Domain\Model\GrupoEstudo\PublicacaoKeyword;
use App\Domain\Repository\KeywordRepositoryInterface;

class PublicacaoKeywordRepository implements KeywordRepositoryInterface
{
    public function saveMany(int $idPublicacao, array $keywords): void
    {
        foreach ($keywords as $word => $count) {
            PublicacaoKeyword::create([
                'id_publicacao' => $idPublicacao,
                'keyword' => $word,
                'frequencia' => $count,
            ]);
        }
    }
}
