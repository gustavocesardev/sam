<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Model\PublicacaoKeyword;
use App\Domain\Repository\KeywordRepositoryInterface;

class KeywordRepository implements KeywordRepositoryInterface
{
    public function saveMany(int $idPublicacao, array $keywords): void
    {
        $data = [];

        foreach ($keywords as $word => $count) {

            $data[] = [
                'id_publicacao' => $idPublicacao,
                'keyword' => $word,
                'frequencia' => $count,
            ];
        }

        PublicacaoKeyword::create($data);
    }
}
