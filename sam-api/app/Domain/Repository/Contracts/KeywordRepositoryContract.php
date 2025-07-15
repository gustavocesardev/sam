<?php

namespace App\Domain\Repository\Contracts;

use Illuminate\Support\Collection;

interface KeywordRepositoryContract
{
    public function saveMany(int $idPublicacao, array $keywords): void;
    public function findByPublicacao(int $idPublicacao): array;
    public function getKeywordsByPublicacao(int $idPublicacao): Collection;
}
