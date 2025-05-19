<?php

namespace App\Domain\Repository;

interface KeywordRepositoryInterface
{
    public function saveMany(int $idPublicacao, array $keywords): void;
}
