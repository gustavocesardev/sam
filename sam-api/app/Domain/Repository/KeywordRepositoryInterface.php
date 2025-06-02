<?php

namespace App\Domain\Repository;

use Illuminate\Database\Eloquent\Collection;

interface KeywordRepositoryInterface
{
    public function saveMany(int $idPublicacao, array $keywords): void;
    public function findByPublicacao(int $idPublicacao): array;
    public function getKeywordsByPublicacao(int $idPublicacao): Collection;
}
