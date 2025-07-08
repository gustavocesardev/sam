<?php

namespace App\Domain\Repository\Abstract;

use Illuminate\Database\Eloquent\Collection;

interface KeywordRepositoryAbstract
{
    public function saveMany(int $idPublicacao, array $keywords): void;
    public function findByPublicacao(int $idPublicacao): array;
    public function getKeywordsByPublicacao(int $idPublicacao): Collection;
}
