<?php

namespace App\Domain\Repository\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface KeywordRepositoryContract
{
    public function saveMany(int $idPublicacao, array $keywords): void;
    public function findByPublicacao(int $idPublicacao): array;
    public function getKeywordsByPublicacao(int $idPublicacao): Collection;
}
