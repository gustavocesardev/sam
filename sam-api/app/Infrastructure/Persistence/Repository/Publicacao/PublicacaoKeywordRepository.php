<?php

namespace App\Infrastructure\Persistence\Repository\Publicacao;

use App\Domain\Model\Publicacao\PublicacaoKeyword;
use App\Domain\Repository\KeywordRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

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

    public function findByPublicacao(int $idPublicacao): array
    {
        return PublicacaoKeyword::where('id_publicacao', $idPublicacao)->pluck('keyword')->all();
    }

    public function getKeywordsByPublicacao(int $idPublicacao): Collection
    {
        return PublicacaoKeyword::findOrFail($idPublicacao)
            ->keywords()
            ->pluck('keyword');
    }
}
